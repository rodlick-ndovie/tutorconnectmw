import { Router } from 'express';
import { query, queryOne } from '../../config/db.js';
import { requireAuth } from '../../middleware/auth.js';
import { ok, created, asyncHandler, errors } from '../../utils/http.js';
import { presentTutorCard } from '../tutors/tutors.service.js';
import { nowSql } from '../../utils/time.js';

const router = Router();
router.use(requireAuth);

// List the current user's saved tutors as full cards.
router.get(
  '/',
  asyncHandler(async (req, res) => {
    const rows = await query(
      `SELECT u.*, sp.name AS plan_name, sp.badge_level
       FROM favorites f
       JOIN users u ON u.id = f.tutor_id AND u.deleted_at IS NULL
       LEFT JOIN tutor_subscriptions ts ON ts.user_id = u.id AND ts.status='active'
         AND ts.current_period_end >= NOW()
       LEFT JOIN subscription_plans sp ON sp.id = ts.plan_id
       WHERE f.user_id = :uid
       GROUP BY u.id
       ORDER BY f.created_at DESC`,
      { uid: req.user.id }
    );
    return ok(res, rows.map(presentTutorCard));
  })
);

router.post(
  '/:tutorId',
  asyncHandler(async (req, res) => {
    const tutorId = Number(req.params.tutorId);
    const tutor = await queryOne(
      `SELECT id FROM users WHERE id = :id AND role='trainer' AND deleted_at IS NULL`,
      { id: tutorId }
    );
    if (!tutor) throw errors.notFound('Tutor not found');

    await query(
      `INSERT IGNORE INTO favorites (user_id, tutor_id, created_at) VALUES (:uid, :tid, :now)`,
      { uid: req.user.id, tid: tutorId, now: nowSql() }
    );
    return created(res, { tutorId, favorited: true });
  })
);

router.delete(
  '/:tutorId',
  asyncHandler(async (req, res) => {
    await query(`DELETE FROM favorites WHERE user_id = :uid AND tutor_id = :tid`, {
      uid: req.user.id,
      tid: Number(req.params.tutorId),
    });
    return ok(res, { tutorId: Number(req.params.tutorId), favorited: false });
  })
);

export default router;
