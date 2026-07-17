import path from 'node:path';
import fs from 'node:fs';
import { Router } from 'express';
import { z } from 'zod';
import multer from 'multer';
import { env } from '../../config/env.js';
import { requireAuth } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { ok, created, asyncHandler } from '../../utils/http.js';
import {
  SERVICE_CATEGORIES,
  TEACHING_MODES,
  WORK_STATUS_OPTIONS,
  ACCOUNT_TYPES,
  DAYS,
  PREFERRED_TIMES,
  UNIVERSITY_PLANS,
} from '../../constants/university.js';
import {
  listUniversityTutors,
  getUniversityTutor,
  getMyUniversityProfile,
  registerUniversity,
  createLectureRequest,
  listLectureRequests,
  applyToLectureRequest,
} from './university.service.js';

const router = Router();

// ---- Uploads (profile photo, national ID, certificates) ----
// Anchored to the backend root (see env.uploads.absDir), independent of cwd.
const uploadsRoot = env.uploads.absDir;
const uniDir = path.join(uploadsRoot, 'university');
fs.mkdirSync(uniDir, { recursive: true });

const upload = multer({
  storage: multer.diskStorage({
    destination: (_req, _file, cb) => cb(null, uniDir),
    filename: (_req, file, cb) => {
      const ext = (path.extname(file.originalname) || '').toLowerCase();
      const safe = file.fieldname.replace(/[^a-z0-9_]/gi, '');
      cb(null, `${safe}_${Date.now()}_${Math.floor(Math.random() * 9000 + 1000)}${ext}`);
    },
  }),
  limits: { fileSize: 8 * 1024 * 1024 },
  fileFilter: (_req, file, cb) => cb(null, /^(image\/|application\/pdf)/.test(file.mimetype)),
});

// ---- Meta (dropdown options — same as the website) ----
router.get('/meta', (req, res) =>
  ok(res, {
    serviceCategories: SERVICE_CATEGORIES,
    teachingModes: TEACHING_MODES,
    workStatusOptions: WORK_STATUS_OPTIONS,
    accountTypes: ACCOUNT_TYPES,
    days: DAYS,
    preferredTimes: PREFERRED_TIMES,
    plans: UNIVERSITY_PLANS,
  })
);

// ---- Public directory ----
const listQuery = z.object({
  accountType: z.enum(['individual', 'firm']).optional(),
  category: z.string().optional(),
  teachingMode: z.enum(['Online', 'Physical', 'Both']).optional(),
  q: z.string().optional(),
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

router.get(
  '/tutors',
  validate(listQuery, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit, ...filters } = req.query;
    const { items, total } = await listUniversityTutors(filters, { page, limit });
    return ok(res, items, { page, limit, total });
  })
);

router.get(
  '/tutors/:id',
  asyncHandler(async (req, res) => ok(res, await getUniversityTutor(Number(req.params.id))))
);

// ---- Registration (individual OR firm) ----
// Multipart: profilePhoto (1), nationalId (1), certificates (up to 5).
// Array fields (institutions, specializations…) come as JSON strings.
const jsonArray = (v) => {
  if (v === undefined || v === '') return [];
  if (Array.isArray(v)) return v;
  try {
    const parsed = JSON.parse(v);
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return String(v)
      .split(',')
      .map((s) => s.trim())
      .filter(Boolean);
  }
};

const registerSchema = z.object({
  accountType: z.enum(['individual', 'firm']),
  fullName: z.string().min(5, 'Full name / company name is required').max(150),
  firstName: z.string().min(1).max(50),
  lastName: z.string().min(1).max(50),
  email: z.string().email().max(150),
  phone: z.string().min(8).max(30),
  username: z.string().min(3).max(20).regex(/^[a-zA-Z0-9_.]+$/),
  password: z.string().min(8).max(100),
  district: z.string().max(50).optional(),
  cityLocation: z.string().min(1, 'City / location is required').max(150),
  yearOfStudyOrGraduation: z.string().min(1).max(50),
  bio: z.string().min(40, 'Bio must be at least 40 characters').max(2000),
  teachingMode: z.enum(['Online', 'Physical', 'Both']),
  workStatus: z.enum(['Employed', 'Not Employed']).optional(),
  employerName: z.string().max(150).optional(),
  employerContact: z.string().max(100).optional(),
  subscriptionPlan: z.enum(['Basic', 'Standard', 'Premium']).optional(),
  hourlyRate: z.coerce.number().nonnegative().optional(),
  consultationRate: z.coerce.number().nonnegative().optional(),
  dissertationRate: z.coerce.number().nonnegative().optional(),
  examPreparationRate: z.coerce.number().nonnegative().optional(),
  institutions: z.preprocess(jsonArray, z.array(z.string())).optional(),
  specializations: z.preprocess(jsonArray, z.array(z.string())).optional(),
  serviceAreas: z.preprocess(jsonArray, z.array(z.string())).optional(),
  availableDays: z.preprocess(jsonArray, z.array(z.string())).optional(),
  preferredTimes: z.preprocess(jsonArray, z.array(z.string())).optional(),
  references: z.preprocess(jsonArray, z.array(z.any())).optional(),
});

router.post(
  '/register',
  upload.fields([
    { name: 'profilePhoto', maxCount: 1 },
    { name: 'nationalId', maxCount: 1 },
    { name: 'certificates', maxCount: 5 },
  ]),
  validate(registerSchema),
  asyncHandler(async (req, res) => {
    const rel = (f) => (f ? `uploads/university/${f.filename}` : null);
    const files = {
      profilePhoto: rel(req.files?.profilePhoto?.[0]),
      nationalId: rel(req.files?.nationalId?.[0]),
      certificates: (req.files?.certificates ?? []).map(rel),
    };
    return created(res, await registerUniversity(req.body, files));
  })
);

// ---- Lecture / service requests ----
const lectureRequestSchema = z.object({
  fullName: z.string().min(5).max(150),
  email: z.string().email().max(150),
  phone: z.string().min(8).max(30),
  institution: z.string().min(1).max(150),
  serviceCategory: z.string().min(1).max(80),
  topic: z.string().min(1).max(255),
  deliveryMode: z.enum(['Online', 'Physical', 'Both']),
  cityLocation: z.string().min(1).max(150),
  preferredDate: z.string().optional(),
  preferredTime: z.string().max(50).optional(),
  budgetRange: z.string().max(100).optional(),
  notes: z.string().max(2000).optional(),
});

router.post(
  '/lecture-requests',
  validate(lectureRequestSchema),
  asyncHandler(async (req, res) => created(res, await createLectureRequest(req.body)))
);

const pageQuery = z.object({
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

// Open requests — visible to signed-in university tutors (their work pipeline).
router.get(
  '/lecture-requests',
  requireAuth,
  validate(pageQuery, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit } = req.query;
    const { items, total } = await listLectureRequests({ page, limit });
    return ok(res, items, { page, limit, total });
  })
);

router.post(
  '/lecture-requests/:id/apply',
  requireAuth,
  asyncHandler(async (req, res) =>
    ok(res, await applyToLectureRequest(req.user, Number(req.params.id)))
  )
);

// ---- Portal (the signed-in university tutor's own profile) ----
router.get(
  '/me',
  requireAuth,
  asyncHandler(async (req, res) => ok(res, await getMyUniversityProfile(req.user)))
);

export default router;
