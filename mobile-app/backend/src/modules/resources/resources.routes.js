import { Router } from 'express';
import { z } from 'zod';
import { query, queryOne } from '../../config/db.js';
import { validate } from '../../middleware/validate.js';
import { ok, asyncHandler, errors } from '../../utils/http.js';
import { mediaUrl } from '../../utils/media.js';
import { purchasePastPaper, purchaseStatus, resolveDownload } from './purchases.service.js';

const router = Router();

const papersQuery = z.object({
  exam_body: z.string().optional(),
  exam_level: z.string().optional(),
  subject: z.string().optional(),
  year: z.coerce.number().int().optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

function presentPaper(p, { includeFile = false } = {}) {
  return {
    id: p.id,
    examBody: p.exam_body,
    examLevel: p.exam_level,
    subject: p.subject,
    year: p.year,
    title: p.paper_title,
    paperCode: p.paper_code,
    fileSize: p.file_size,
    downloadCount: p.download_count,
    isPaid: !!p.is_paid,
    price: Number(p.price),
    copyrightNotice: p.copyright_notice,
    // Only expose the file for free papers; paid ones require purchase.
    fileUrl: includeFile && !p.is_paid ? mediaUrl(p.file_url) : null,
  };
}

router.get(
  '/past-papers',
  validate(papersQuery, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit, ...f } = req.query;
    const offset = (page - 1) * limit;
    const conds = ['is_active = 1'];
    const params = { limit, offset };
    for (const key of ['exam_body', 'exam_level', 'subject', 'year']) {
      if (f[key] !== undefined) {
        conds.push(`${key} = :${key}`);
        params[key] = f[key];
      }
    }
    const where = conds.join(' AND ');
    const items = await query(
      `SELECT * FROM past_papers WHERE ${where} ORDER BY year DESC, subject ASC LIMIT :limit OFFSET :offset`,
      params
    );
    const countRow = await queryOne(`SELECT COUNT(*) AS total FROM past_papers WHERE ${where}`, params);
    return ok(res, items.map((p) => presentPaper(p)), { page, limit, total: Number(countRow?.total ?? 0) });
  })
);

// ---- Paid past-paper purchase (public — no account needed) ----
const purchaseSchema = z.object({
  buyerName: z.string().min(1, 'Your name is required').max(120),
  buyerEmail: z.string().email('Enter a valid email'),
  buyerPhone: z.string().max(20).optional(),
});

router.post(
  '/past-papers/:id/purchase',
  validate(purchaseSchema),
  asyncHandler(async (req, res) => ok(res, await purchasePastPaper(Number(req.params.id), req.body)))
);

router.get(
  '/past-papers/purchase/:txRef/status',
  asyncHandler(async (req, res) => ok(res, await purchaseStatus(String(req.params.txRef))))
);

// Redirects to the actual file once the purchase is verified.
router.get(
  '/past-papers/download/:token',
  asyncHandler(async (req, res) => {
    const { fileUrl } = await resolveDownload(String(req.params.token));
    return res.redirect(fileUrl);
  })
);

router.get(
  '/past-papers/:id',
  asyncHandler(async (req, res) => {
    const paper = await queryOne(`SELECT * FROM past_papers WHERE id = :id AND is_active = 1`, {
      id: Number(req.params.id),
    });
    if (!paper) throw errors.notFound('Past paper not found');
    return ok(res, presentPaper(paper, { includeFile: true }));
  })
);

const videosQuery = z.object({
  subject: z.string().optional(),
  exam_body: z.string().optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

function presentVideo(v) {
  return {
    id: v.id,
    tutorId: v.tutor_id,
    tutorName: v.tutor_name ? v.tutor_name.trim() : null,
    title: v.title,
    description: v.description,
    platform: v.video_platform,
    videoId: v.video_id,
    embedCode: v.video_embed_code,
    examBody: v.exam_body,
    subject: v.subject,
    topic: v.topic,
    viewCount: v.view_count,
    createdAt: v.created_at,
  };
}

router.get(
  '/videos',
  validate(videosQuery, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit, subject, exam_body } = req.query;
    const offset = (page - 1) * limit;
    const conds = [`v.status = 'approved'`];
    const params = { limit, offset };
    if (subject) {
      conds.push('v.subject = :subject');
      params.subject = subject;
    }
    if (exam_body) {
      conds.push('v.exam_body = :exam_body');
      params.exam_body = exam_body;
    }
    const where = conds.join(' AND ');
    const items = await query(
      `SELECT v.*, CONCAT(u.first_name,' ',u.last_name) AS tutor_name
       FROM tutor_videos v JOIN users u ON u.id = v.tutor_id
       WHERE ${where}
       ORDER BY FIELD(v.featured_level,'premium_featured','standard','none'), v.created_at DESC
       LIMIT :limit OFFSET :offset`,
      params
    );
    const countRow = await queryOne(`SELECT COUNT(*) AS total FROM tutor_videos v WHERE ${where}`, params);
    return ok(res, items.map(presentVideo), { page, limit, total: Number(countRow?.total ?? 0) });
  })
);

router.get(
  '/videos/:id',
  asyncHandler(async (req, res) => {
    const id = Number(req.params.id);
    const v = await queryOne(
      `SELECT v.*, CONCAT(u.first_name,' ',u.last_name) AS tutor_name
       FROM tutor_videos v JOIN users u ON u.id = v.tutor_id
       WHERE v.id = :id AND v.status = 'approved'`,
      { id }
    );
    if (!v) throw errors.notFound('Video not found');
    await query(`UPDATE tutor_videos SET view_count = view_count + 1 WHERE id = :id`, { id });
    return ok(res, presentVideo(v));
  })
);

export default router;
