import { Router } from 'express';
import { z } from 'zod';
import { query, queryOne } from '../../config/db.js';
import { requireAuth, requireRole } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { ok, asyncHandler, errors } from '../../utils/http.js';
import { nowSql } from '../../utils/time.js';
import { mediaUrl } from '../../utils/media.js';
import { notify } from '../notifications/notifications.service.js';

const router = Router();
router.use(requireAuth, requireRole('admin', 'sub-admin'));

// Dashboard counters for on-the-go admin.
router.get(
  '/overview',
  asyncHandler(async (req, res) => {
    const [[{ pending_tutors }], [{ pending_payments }], [{ pending_videos }], [{ pending_notices }], [{ total_users }]] =
      await Promise.all([
        query(`SELECT COUNT(*) AS pending_tutors FROM users WHERE role='trainer' AND tutor_status='pending' AND deleted_at IS NULL`),
        query(`SELECT COUNT(*) AS pending_payments FROM tutor_subscriptions WHERE payment_status='pending' AND payment_amount > 0`),
        query(`SELECT COUNT(*) AS pending_videos FROM tutor_videos WHERE status='pending_review'`),
        query(`SELECT COUNT(*) AS pending_notices FROM notices WHERE status='pending'`),
        query(`SELECT COUNT(*) AS total_users FROM users WHERE deleted_at IS NULL`),
      ]);
    return ok(res, {
      pendingTutors: Number(pending_tutors),
      pendingPayments: Number(pending_payments),
      pendingVideos: Number(pending_videos),
      pendingNotices: Number(pending_notices),
      totalUsers: Number(total_users),
    });
  })
);

// Tutor review queue.
const tutorListSchema = z.object({
  status: z.enum(['pending', 'approved', 'suspended', 'inactive', 'rejected']).optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

router.get(
  '/tutors',
  validate(tutorListSchema, 'query'),
  asyncHandler(async (req, res) => {
    const { status, page, limit } = req.query;
    const offset = (page - 1) * limit;
    const where = status ? 'AND tutor_status = :status' : '';
    const params = { limit, offset, ...(status ? { status } : {}) };
    const items = await query(
      `SELECT id, first_name, last_name, email, phone, district, tutor_status, is_verified,
              profile_picture, verification_documents, created_at
       FROM users WHERE role='trainer' AND deleted_at IS NULL ${where}
       ORDER BY created_at DESC LIMIT :limit OFFSET :offset`,
      params
    );
    const countRow = await queryOne(
      `SELECT COUNT(*) AS total FROM users WHERE role='trainer' AND deleted_at IS NULL ${where}`,
      status ? { status } : {}
    );
    return ok(
      res,
      items.map((u) => ({
        id: u.id,
        name: `${u.first_name ?? ''} ${u.last_name ?? ''}`.trim(),
        email: u.email,
        phone: u.phone,
        district: u.district,
        tutorStatus: u.tutor_status,
        isVerified: !!u.is_verified,
        profilePicture: mediaUrl(u.profile_picture),
        hasDocuments: !!u.verification_documents,
        createdAt: u.created_at,
      })),
      { page, limit, total: Number(countRow?.total ?? 0) }
    );
  })
);

async function setTutorStatus(id, status, { setApprovedAt = false } = {}) {
  const tutor = await queryOne(`SELECT id FROM users WHERE id = :id AND role='trainer'`, { id });
  if (!tutor) throw errors.notFound('Tutor not found');
  const now = nowSql();
  const approvedAt = setApprovedAt ? ', approved_at = :now' : '';
  await query(`UPDATE users SET tutor_status = :status, updated_at = :now ${approvedAt} WHERE id = :id`, {
    status,
    now,
    id,
  });
}

router.post(
  '/tutors/:id/approve',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    await setTutorStatus(id, 'approved', { setApprovedAt: true });
    notify(id, { type: 'tutor_approved', title: 'Profile approved', body: 'Your tutor profile is now live.' }).catch(() => {});
    return ok(res, { id, tutorStatus: 'approved' });
  })
);

router.post(
  '/tutors/:id/reject',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    await setTutorStatus(id, 'rejected');
    notify(id, { type: 'tutor_rejected', title: 'Profile update needed', body: 'Please review and resubmit your documents.' }).catch(() => {});
    return ok(res, { id, tutorStatus: 'rejected' });
  })
);

router.post(
  '/tutors/:id/suspend',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    await setTutorStatus(id, 'suspended');
    return ok(res, { id, tutorStatus: 'suspended' });
  })
);

router.post(
  '/tutors/:id/activate',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    await setTutorStatus(id, 'approved');
    return ok(res, { id, tutorStatus: 'approved' });
  })
);

// Payment review queue (manual proof verification).
router.get(
  '/payments',
  asyncHandler(async (req, res) => {
    const items = await query(
      `SELECT s.id, s.user_id, s.payment_reference, s.payment_amount, s.payment_status,
              s.payment_proof_file, s.created_at, p.name AS plan_name,
              CONCAT(u.first_name,' ',u.last_name) AS tutor_name
       FROM tutor_subscriptions s
       JOIN subscription_plans p ON p.id = s.plan_id
       JOIN users u ON u.id = s.user_id
       WHERE s.payment_status = 'pending' AND s.payment_amount > 0
       ORDER BY s.created_at DESC`
    );
    return ok(
      res,
      items.map((s) => ({
        id: s.id,
        userId: s.user_id,
        tutorName: s.tutor_name?.trim(),
        planName: s.plan_name,
        reference: s.payment_reference,
        amount: Number(s.payment_amount),
        proof: mediaUrl(s.payment_proof_file),
        createdAt: s.created_at,
      }))
    );
  })
);

const decisionSchema = z.object({ decision: z.enum(['verify', 'reject']) });

router.post(
  '/payments/:id/decision',
  validate(decisionSchema),
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    const sub = await queryOne(`SELECT * FROM tutor_subscriptions WHERE id = :id`, { id });
    if (!sub) throw errors.notFound('Subscription not found');
    const now = nowSql();

    if (req.body.decision === 'verify') {
      await query(
        `UPDATE tutor_subscriptions SET payment_status='verified', status='active', payment_date=:now, updated_at=:now WHERE id=:id`,
        { now, id }
      );
      await query(
        `UPDATE users u JOIN subscription_plans p ON p.id = :pid
         SET u.subscription_plan = p.name, u.subscription_expires_at = DATE(:end), u.updated_at = :now
         WHERE u.id = :uid`,
        { pid: sub.plan_id, end: sub.current_period_end, now, uid: sub.user_id }
      );
      notify(sub.user_id, { type: 'payment_verified', title: 'Payment verified', body: 'Your subscription is now active.' }).catch(() => {});
    } else {
      await query(
        `UPDATE tutor_subscriptions SET payment_status='rejected', status='inactive', updated_at=:now WHERE id=:id`,
        { now, id }
      );
    }
    return ok(res, { id, decision: req.body.decision });
  })
);

// Notice & video approval.
router.post(
  '/notices/:id/:decision',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    const decision = req.params.decision === 'approve' ? 'approved' : 'rejected';
    await query(`UPDATE notices SET status = :status, approved_by = :admin, approved_at = :now WHERE id = :id`, {
      status: decision,
      admin: req.user.id,
      now: nowSql(),
      id,
    });
    return ok(res, { id, status: decision });
  })
);

router.post(
  '/videos/:id/:decision',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    const decision = req.params.decision === 'approve' ? 'approved' : 'rejected';
    const now = nowSql();
    await query(
      `UPDATE tutor_videos SET status = :status, approved_at = :approvedAt, updated_at = :now WHERE id = :id`,
      { status: decision, approvedAt: decision === 'approved' ? now : null, now, id }
    );
    return ok(res, { id, status: decision });
  })
);

export default router;
