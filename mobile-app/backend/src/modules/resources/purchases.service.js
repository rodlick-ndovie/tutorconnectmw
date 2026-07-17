import crypto from 'node:crypto';
import { query, queryOne } from '../../config/db.js';
import { errors } from '../../utils/http.js';
import { nowSql } from '../../utils/time.js';
import { mediaUrl } from '../../utils/media.js';
import { env } from '../../config/env.js';
import {
  initializePayment,
  verifyPayment,
  isSuccessfulVerification,
  paychanguConfigured,
} from '../../config/paychangu.js';

// Absolute URL to the download endpoint (served by this API).
function downloadUrlFor(token) {
  const base = (env.uploads.publicBaseUrl || '').replace(/\/$/, '');
  return `${base}${env.apiPrefix}/resources/past-papers/download/${token}`;
}

/**
 * Start a PUBLIC past-paper purchase (no account needed — mirrors the website's
 * paid-download flow). Creates a pending past_paper_purchases row + PayChangu
 * checkout. Access is granted only after payment is verified.
 */
export async function purchasePastPaper(paperId, buyer) {
  const paper = await queryOne(`SELECT * FROM past_papers WHERE id = :id AND is_active = 1`, {
    id: paperId,
  });
  if (!paper) throw errors.notFound('Past paper not found');
  if (!paper.is_paid || Number(paper.price) <= 0) throw errors.badRequest('This paper is free to download.');
  if (!paychanguConfigured()) {
    throw errors.badRequest('Payments are not configured on the server (PAYCHANGU keys missing).');
  }

  const amount = Number(paper.price);
  const txRef = `PP-${paperId}-${Date.now()}-${crypto.randomBytes(3).toString('hex')}`;
  const accessToken = crypto.randomBytes(24).toString('hex');
  const now = nowSql();

  await query(
    `INSERT INTO past_paper_purchases
       (past_paper_id, user_id, tx_ref, buyer_name, buyer_email, buyer_phone, amount, currency,
        payment_method, payment_status, access_token, created_at, updated_at)
     VALUES
       (:pid, NULL, :tx, :name, :email, :phone, :amount, 'MWK',
        'paychangu', 'pending', :token, :now, :now)`,
    {
      pid: paperId,
      tx: txRef,
      name: buyer.buyerName,
      email: buyer.buyerEmail,
      phone: buyer.buyerPhone ?? null,
      amount,
      token: accessToken,
      now,
    }
  );

  const parts = String(buyer.buyerName).trim().split(/\s+/);
  const init = await initializePayment({
    amount,
    currency: 'MWK',
    email: buyer.buyerEmail,
    firstName: parts[0] || 'Student',
    lastName: parts.slice(1).join(' ') || '.',
    callbackUrl: env.paychangu.callbackUrl,
    returnUrl: env.paychangu.returnUrl,
    txRef,
    title: `Past paper: ${paper.subject} ${paper.year}`,
    description: 'TutorConnect past paper purchase',
  });

  const checkoutUrl = init?.data?.checkout_url ?? init?.checkout_url ?? null;
  return { txRef, accessToken, amount, checkoutUrl, raw: init?.status };
}

/** Verify the payment; on success mark the purchase verified (parity with web). */
export async function purchaseStatus(txRef) {
  const p = await queryOne(`SELECT * FROM past_paper_purchases WHERE tx_ref = :tx`, { tx: txRef });
  if (!p) throw errors.notFound('Purchase not found');
  if (p.payment_status === 'verified') {
    return { status: 'paid', accessToken: p.access_token, downloadUrl: downloadUrlFor(p.access_token) };
  }

  const result = await verifyPayment(txRef);
  const paid = isSuccessfulVerification(result, { txRef, currency: 'MWK', amount: Number(p.amount) });
  if (paid) {
    const now = nowSql();
    await query(
      `UPDATE past_paper_purchases
         SET payment_status = 'verified', paid_at = :now, download_granted_at = :now, updated_at = :now
       WHERE id = :id`,
      { now, id: p.id }
    );
    return { status: 'paid', accessToken: p.access_token, downloadUrl: downloadUrlFor(p.access_token) };
  }
  return { status: 'pending' };
}

/** Resolve a verified purchase's access token to the actual file URL. */
export async function resolveDownload(accessToken) {
  const p = await queryOne(
    `SELECT * FROM past_paper_purchases WHERE access_token = :t AND payment_status = 'verified'`,
    { t: accessToken }
  );
  if (!p) throw errors.forbidden('This download is not available yet. Payment may still be processing.');
  const paper = await queryOne(`SELECT file_url FROM past_papers WHERE id = :id`, { id: p.past_paper_id });
  if (!paper || !paper.file_url) throw errors.notFound('File not available.');

  const now = nowSql();
  await query(
    `UPDATE past_paper_purchases
       SET download_count = download_count + 1, last_downloaded_at = :now, updated_at = :now
     WHERE id = :id`,
    { now, id: p.id }
  );
  return { fileUrl: mediaUrl(paper.file_url) };
}
