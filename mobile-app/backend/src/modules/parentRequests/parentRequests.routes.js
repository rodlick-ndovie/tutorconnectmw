import crypto from 'node:crypto';
import { Router } from 'express';
import { z } from 'zod';
import { query, queryOne } from '../../config/db.js';
import { requireAuth, requireApprovedTutor } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { ok, created, asyncHandler, errors } from '../../utils/http.js';
import { nowSql } from '../../utils/time.js';
import { safeJsonParse } from '../../utils/media.js';

const router = Router();

function present(r) {
  return {
    id: r.id,
    referenceCode: r.reference_code,
    curriculum: r.curriculum,
    gradeClass: r.grade_class,
    subjects: safeJsonParse(r.subjects_json, []),
    district: r.district,
    specificLocation: r.specific_location,
    mode: r.mode,
    budgetMin: r.budget_min != null ? Number(r.budget_min) : null,
    budgetMax: r.budget_max != null ? Number(r.budget_max) : null,
    budgetPeriod: r.budget_period,
    notes: r.notes,
    status: r.status,
    createdAt: r.created_at,
  };
}

const listSchema = z.object({
  district: z.string().optional(),
  curriculum: z.string().optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

// Open requests tutors can browse.
router.get(
  '/',
  validate(listSchema, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit, district, curriculum } = req.query;
    const offset = (page - 1) * limit;
    const conds = [`status = 'open'`];
    const params = { limit, offset };
    if (district) {
      conds.push('district = :district');
      params.district = district;
    }
    if (curriculum) {
      conds.push('curriculum = :curriculum');
      params.curriculum = curriculum;
    }
    const where = conds.join(' AND ');
    const items = await query(
      `SELECT * FROM parent_requests WHERE ${where} ORDER BY created_at DESC LIMIT :limit OFFSET :offset`,
      params
    );
    const countRow = await queryOne(`SELECT COUNT(*) AS total FROM parent_requests WHERE ${where}`, params);
    return ok(res, items.map(present), { page, limit, total: Number(countRow?.total ?? 0) });
  })
);

router.get(
  '/:id',
  asyncHandler(async (req, res) => {
    const r = await queryOne(`SELECT * FROM parent_requests WHERE id = :id`, { id: Number(req.params.id) });
    if (!r) throw errors.notFound('Request not found');
    return ok(res, present(r));
  })
);

const createSchema = z.object({
  curriculum: z.string().max(50),
  gradeClass: z.string().max(100),
  subjects: z.array(z.string()).default([]),
  district: z.string().max(50),
  specificLocation: z.string().max(255).optional(),
  mode: z.enum(['online', 'in-person', 'both']).default('both'),
  budgetMin: z.coerce.number().min(0).optional(),
  budgetMax: z.coerce.number().min(0).optional(),
  budgetPeriod: z.string().max(20).optional(),
  notes: z.string().max(2000).optional(),
  parentPhone: z.string().max(30),
  parentEmail: z.string().email().max(150),
});

router.post(
  '/',
  validate(createSchema),
  asyncHandler(async (req, res) => {
    const b = req.body;
    const now = nowSql();
    const reference = `PR-${now.slice(0, 10).replace(/-/g, '')}-${crypto.randomBytes(3).toString('hex').toUpperCase()}`;
    const rows = await query(
      `INSERT INTO parent_requests
         (reference_code, curriculum, grade_class, subjects_json, district, specific_location, mode,
          budget_min, budget_max, budget_period, notes, parent_phone, parent_email, status,
          matched_tutor_count, emailed_tutor_count, created_at, updated_at)
       VALUES (:ref, :curriculum, :grade, :subjects, :district, :loc, :mode,
               :bmin, :bmax, :bperiod, :notes, :phone, :email, 'open', 0, 0, :now, :now)`,
      {
        ref: reference,
        curriculum: b.curriculum,
        grade: b.gradeClass,
        subjects: JSON.stringify(b.subjects),
        district: b.district,
        loc: b.specificLocation ?? null,
        mode: b.mode,
        bmin: b.budgetMin ?? null,
        bmax: b.budgetMax ?? null,
        bperiod: b.budgetPeriod ?? null,
        notes: b.notes ?? null,
        phone: b.parentPhone,
        email: b.parentEmail,
        now,
      }
    );
    return created(res, { id: rows.insertId, referenceCode: reference });
  })
);

// A tutor applies to a request.
router.post(
  '/:id/apply',
  requireAuth,
  requireApprovedTutor,
  asyncHandler(async (req, res) => {
    const requestId = Number(req.params.id);
    const request = await queryOne(`SELECT id FROM parent_requests WHERE id = :id AND status = 'open'`, {
      id: requestId,
    });
    if (!request) throw errors.notFound('Request not available');

    const existing = await queryOne(
      `SELECT id FROM parent_request_applications WHERE parent_request_id = :rid AND tutor_id = :tid`,
      { rid: requestId, tid: req.user.id }
    );
    if (existing) throw errors.conflict('You already applied to this request');

    const now = nowSql();
    await query(
      `INSERT INTO parent_request_applications
         (parent_request_id, tutor_id, tutor_email, status, applied_at, created_at, updated_at)
       VALUES (:rid, :tid, :email, 'applied', :now, :now, :now)`,
      { rid: requestId, tid: req.user.id, email: req.user.email, now }
    );
    await query(
      `UPDATE parent_requests SET matched_tutor_count = matched_tutor_count + 1, updated_at = :now WHERE id = :id`,
      { now, id: requestId }
    );
    return created(res, { applied: true });
  })
);

export default router;
