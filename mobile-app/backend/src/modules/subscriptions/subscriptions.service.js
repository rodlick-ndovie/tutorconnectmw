import crypto from 'node:crypto';
import { pool, query, queryOne } from '../../config/db.js';
import { errors } from '../../utils/http.js';
import { nowSql } from '../../utils/time.js';
import { env } from '../../config/env.js';
import {
  initializePayment,
  verifyPayment,
  isSuccessfulVerification,
  paychanguConfigured,
} from '../../config/paychangu.js';

function presentPlan(p) {
  return {
    id: p.id,
    name: p.name,
    description: p.description,
    priceMonthly: Number(p.price_monthly),
    badgeLevel: p.badge_level,
    searchRanking: p.search_ranking,
    features: {
      maxSubjects: p.max_subjects,
      maxReviews: p.max_reviews,
      maxMessages: p.max_messages,
      showWhatsapp: !!p.show_whatsapp,
      allowVideoUpload: !!p.allow_video_upload,
      allowPdfUpload: !!p.allow_pdf_upload,
      allowAnnouncements: !!p.allow_announcements,
      districtSpotlightDays: p.district_spotlight_days,
    },
    sortOrder: p.sort_order,
  };
}

export async function listPlans() {
  const rows = await query(
    `SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY sort_order ASC, price_monthly ASC`
  );
  return rows.map(presentPlan);
}

export async function getMySubscription(userId) {
  const sub = await queryOne(
    `SELECT s.*, p.name AS plan_name, p.price_monthly, p.max_subjects
     FROM tutor_subscriptions s
     JOIN subscription_plans p ON p.id = s.plan_id
     WHERE s.user_id = :uid
     ORDER BY (s.status = 'active') DESC, s.current_period_end DESC
     LIMIT 1`,
    { uid: userId }
  );
  if (!sub) return null;
  return {
    id: sub.id,
    planId: sub.plan_id,
    planName: sub.plan_name,
    status: sub.status,
    billingMonths: sub.billing_months,
    currentPeriodStart: sub.current_period_start,
    currentPeriodEnd: sub.current_period_end,
    paymentStatus: sub.payment_status,
    paymentAmount: sub.payment_amount != null ? Number(sub.payment_amount) : null,
    isActive: sub.status === 'active' && new Date(sub.current_period_end) >= new Date(),
    maxSubjects: Number(sub.max_subjects) || 0, // 0 = unlimited
  };
}

function addMonths(months) {
  const d = new Date();
  d.setMonth(d.getMonth() + months);
  return d.toISOString().slice(0, 19).replace('T', ' ');
}

/**
 * Create a PENDING subscription and initialize a PayChangu payment.
 * The subscription only becomes active once the payment is verified.
 */
export async function checkout(user, { planId, billingMonths }) {
  const plan = await queryOne(`SELECT * FROM subscription_plans WHERE id = :id AND is_active = 1`, {
    id: planId,
  });
  if (!plan) throw errors.notFound('Plan not found');

  const amount = Number(plan.price_monthly) * billingMonths;
  const txRef = `TXN-${user.id}-${Date.now()}-${crypto.randomBytes(4).toString('hex')}`;
  const now = nowSql();

  const rows = await query(
    `INSERT INTO tutor_subscriptions
       (user_id, plan_id, billing_months, status, current_period_start, current_period_end,
        payment_method, payment_reference, payment_amount, payment_status, created_at, updated_at, terms_accepted)
     VALUES (:uid, :planId, :months, 'pending', :now, :end, 'mobile_money', :ref, :amount, 'pending', :now, :now, 1)`,
    { uid: user.id, planId, months: billingMonths, now, end: addMonths(billingMonths), ref: txRef, amount }
  );

  // Free plans activate immediately, no payment needed.
  if (amount <= 0) {
    await activateSubscription(rows.insertId);
    return { subscriptionId: rows.insertId, txRef, amount, free: true };
  }

  if (!paychanguConfigured()) {
    throw errors.badRequest('Payments are not configured on the server (PAYCHANGU keys missing).');
  }

  const init = await initializePayment({
    amount,
    currency: 'MWK',
    email: user.email,
    firstName: user.first_name,
    lastName: user.last_name,
    callbackUrl: env.paychangu.callbackUrl,
    returnUrl: env.paychangu.returnUrl,
    txRef,
    title: `TutorConnect ${plan.name} plan`,
  });

  const checkoutUrl = init?.data?.checkout_url ?? init?.checkout_url ?? null;
  return { subscriptionId: rows.insertId, txRef, amount, checkoutUrl, raw: init?.status };
}

async function activateSubscription(subscriptionId) {
  const sub = await queryOne(`SELECT * FROM tutor_subscriptions WHERE id = :id`, { id: subscriptionId });
  if (!sub) return;
  const now = nowSql();
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();
    // Retire any other active subs for this user, then activate this one.
    await conn.execute(
      `UPDATE tutor_subscriptions SET status = 'expired', updated_at = :now
       WHERE user_id = :uid AND id <> :id AND status = 'active'`,
      { now, uid: sub.user_id, id: subscriptionId }
    );
    await conn.execute(
      `UPDATE tutor_subscriptions
       SET status = 'active', payment_status = 'verified', payment_date = :now, updated_at = :now
       WHERE id = :id`,
      { now, id: subscriptionId }
    );
    await conn.execute(
      `UPDATE users u
         JOIN subscription_plans p ON p.id = :planId
         SET u.subscription_plan = p.name,
             u.subscription_expires_at = DATE(:end),
             u.updated_at = :now
       WHERE u.id = :uid`,
      { planId: sub.plan_id, end: sub.current_period_end, now, uid: sub.user_id }
    );
    await conn.commit();
  } catch (e) {
    await conn.rollback();
    throw e;
  } finally {
    conn.release();
  }
}

/** Verify a tx_ref against PayChangu and activate the subscription if paid. */
export async function syncPayment(txRef) {
  const sub = await queryOne(
    `SELECT * FROM tutor_subscriptions WHERE payment_reference = :ref ORDER BY id DESC LIMIT 1`,
    { ref: txRef }
  );
  if (!sub) throw errors.notFound('No subscription for that reference');

  if (sub.payment_status === 'verified') {
    return { txRef, status: 'verified', subscriptionId: sub.id };
  }

  const result = await verifyPayment(txRef);
  const success = isSuccessfulVerification(result, {
    txRef,
    currency: 'MWK',
    amount: sub.payment_amount != null ? Number(sub.payment_amount) : null,
  });

  if (success) {
    await activateSubscription(sub.id);
    return { txRef, status: 'verified', subscriptionId: sub.id };
  }
  return { txRef, status: 'pending', subscriptionId: sub.id };
}
