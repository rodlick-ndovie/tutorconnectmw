import { Router } from 'express';
import { query } from '../../config/db.js';
import { ok, asyncHandler } from '../../utils/http.js';
import { MALAWI_DISTRICTS_SORTED } from '../../constants/districts.js';

const router = Router();

/**
 * All 28 districts of Malawi (same list as the website's dropdowns).
 *
 * This used to return only districts that already had a listable tutor, which
 * meant sign-up offered a partial list and a tutor literally could not pick
 * their own district. Pass ?withTutors=1 for the narrower "districts that
 * currently have tutors" set (useful for search facets).
 */
router.get(
  '/districts',
  asyncHandler(async (req, res) => {
    if (!req.query.withTutors) return ok(res, MALAWI_DISTRICTS_SORTED);

    const rows = await query(
      `SELECT DISTINCT users.district
       FROM users
       JOIN tutor_subscriptions ts ON ts.user_id = users.id AND ts.status = 'active'
         AND ts.current_period_end >= NOW()
       LEFT JOIN university_college_tutors uct
         ON uct.user_id = users.id OR uct.email = users.email
       WHERE users.role='trainer' AND users.tutor_status='approved'
         AND users.is_verified=1 AND users.is_active=1 AND users.deleted_at IS NULL
         AND uct.id IS NULL AND users.district IS NOT NULL AND users.district <> ''
       ORDER BY users.district ASC`
    );
    return ok(res, rows.map((r) => r.district));
  })
);

// Curriculum metadata sourced from the curriculum_subjects reference table
// (parity with the website's Api::getCurriculumLevels / getCurriculumSubjects).
router.get(
  '/curricula',
  asyncHandler(async (req, res) => {
    const rows = await query(
      `SELECT DISTINCT curriculum FROM curriculum_subjects WHERE is_active = 1 ORDER BY curriculum ASC`
    );
    return ok(res, rows.map((r) => r.curriculum));
  })
);

router.get(
  '/levels',
  asyncHandler(async (req, res) => {
    const { curriculum } = req.query;
    const rows = await query(
      `SELECT DISTINCT level_name FROM curriculum_subjects
       WHERE is_active = 1 ${curriculum ? 'AND curriculum = :curriculum' : ''}
       ORDER BY level_name ASC`,
      curriculum ? { curriculum } : {}
    );
    return ok(res, rows.map((r) => r.level_name));
  })
);

router.get(
  '/subjects',
  asyncHandler(async (req, res) => {
    const { curriculum, level } = req.query;
    const conds = ['is_active = 1'];
    const params = {};
    if (curriculum) {
      conds.push('curriculum = :curriculum');
      params.curriculum = curriculum;
    }
    if (level) {
      conds.push('level_name = :level');
      params.level = level;
    }
    const rows = await query(
      `SELECT DISTINCT subject_name FROM curriculum_subjects
       WHERE ${conds.join(' AND ')} ORDER BY subject_name ASC`,
      params
    );
    return ok(res, rows.map((r) => r.subject_name));
  })
);

export default router;
