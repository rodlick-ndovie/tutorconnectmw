import { Router } from 'express';
import { z } from 'zod';
import { query, queryOne } from '../../config/db.js';
import { validate } from '../../middleware/validate.js';
import { ok, asyncHandler, errors } from '../../utils/http.js';
import { mediaUrl } from '../../utils/media.js';

const router = Router();

// The "Tutor's Success Stories" feed = approved notices/announcements.
const listSchema = z.object({
  type: z.enum(['Vacancy', 'Notice', 'Announcement']).optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

function present(n) {
  return {
    id: n.id,
    schoolName: n.school_name,
    schoolType: n.school_type,
    noticeType: n.notice_type,
    title: n.notice_title,
    content: n.notice_content,
    image: mediaUrl(n.attached_image),
    phone: n.phone,
    email: n.email,
    viewsCount: n.views_count,
    createdAt: n.created_at,
  };
}

router.get(
  '/',
  validate(listSchema, 'query'),
  asyncHandler(async (req, res) => {
    const { type, page, limit } = req.query;
    const offset = (page - 1) * limit;
    const where = type ? `AND notice_type = :type` : '';
    const params = { limit, offset, ...(type ? { type } : {}) };

    const items = await query(
      `SELECT * FROM notices
       WHERE status = 'approved' ${where}
       ORDER BY created_at DESC
       LIMIT :limit OFFSET :offset`,
      params
    );
    const countRow = await queryOne(
      `SELECT COUNT(*) AS total FROM notices WHERE status='approved' ${where}`,
      type ? { type } : {}
    );
    return ok(res, items.map(present), { page, limit, total: Number(countRow?.total ?? 0) });
  })
);

router.get(
  '/:id',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    const notice = await queryOne(`SELECT * FROM notices WHERE id = :id AND status='approved'`, { id });
    if (!notice) throw errors.notFound('Notice not found');
    // Best-effort view increment (parity with website behavior).
    await query(`UPDATE notices SET views_count = views_count + 1 WHERE id = :id`, { id });
    return ok(res, present(notice));
  })
);

export default router;
