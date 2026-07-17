import path from 'node:path';
import fs from 'node:fs';
import { Router } from 'express';
import { z } from 'zod';
import multer from 'multer';
import { requireAuth, requireApprovedTutor } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { env } from '../../config/env.js';
import { ok, asyncHandler, errors } from '../../utils/http.js';
import {
  getMyProfile,
  updateMyProfile,
  updateAvailability,
  updateStructuredSubjects,
  setUserImage,
  addVerificationDocument,
  completeResubmission,
  getAnalytics,
  changePassword,
  getMyEnquiries,
  markEnquiryRead,
} from './me.service.js';

const router = Router();
router.use(requireAuth);

// ---- File uploads ----
// Anchored to the backend root (env.uploads.absDir), not process.cwd(), so files
// are always written inside the backend and match where /uploads serves from.
const uploadsRoot = env.uploads.absDir;

function makeUploader(subfolder, buildName, { imagesOnly = false } = {}) {
  const dir = path.join(uploadsRoot, subfolder);
  fs.mkdirSync(dir, { recursive: true });
  const storage = multer.diskStorage({
    destination: (_req, _file, cb) => cb(null, dir),
    filename: (req, file, cb) => {
      const ext = (path.extname(file.originalname) || '').toLowerCase();
      cb(null, buildName(req, ext));
    },
  });
  return multer({
    storage,
    limits: { fileSize: 8 * 1024 * 1024 }, // 8 MB
    fileFilter: (_req, file, cb) => {
      const okType = imagesOnly
        ? /^image\//.test(file.mimetype)
        : /^(image\/|application\/pdf)/.test(file.mimetype);
      cb(null, okType);
    },
  });
}

const photoUpload = makeUploader(
  'profile_photos',
  (req, ext) => `profile_${req.user.id}_${Date.now()}${ext || '.jpg'}`,
  { imagesOnly: true }
);
const coverUpload = makeUploader(
  'profile_photos',
  (req, ext) => `cover_${req.user.id}_${Date.now()}${ext || '.jpg'}`,
  { imagesOnly: true }
);
const docUpload = makeUploader('documents', (req, ext) => {
  const type = String(req.query.type || 'document').replace(/[^a-z0-9_]/gi, '_').toLowerCase();
  const rand = Math.floor(Math.random() * 9000 + 1000);
  return `${type}_${req.user.id}_${Date.now()}_${rand}${ext || '.pdf'}`;
});

const profileSchema = z.object({
  firstName: z.string().max(50).optional(),
  lastName: z.string().max(50).optional(),
  phone: z.string().max(20).optional(),
  gender: z.enum(['Male', 'Female', 'Other', 'Prefer not to say']).optional(),
  district: z.string().max(50).optional(),
  location: z.string().max(255).optional(),
  experienceYears: z.coerce.number().int().min(0).max(80).optional(),
  teachingMode: z.string().max(50).optional(),
  bio: z.string().max(5000).optional(),
  whatsappNumber: z.string().max(20).optional(),
  phoneVisible: z.boolean().optional(),
  emailVisible: z.boolean().optional(),
  bestCallTime: z.string().max(50).optional(),
  preferredContactMethod: z.string().max(20).optional(),
  isEmployed: z.boolean().optional(),
  schoolName: z.string().max(100).optional(),
});

// availability: { days: string[], times: string[] }
const availabilitySchema = z.object({
  days: z.array(z.string()).default([]),
  times: z.array(z.string()).default([]),
});

// structured_subjects: { [curriculum]: { levels: { [level]: string[] } } }
const subjectsSchema = z.record(
  z.object({ levels: z.record(z.array(z.string())) })
);

router.get('/profile', asyncHandler(async (req, res) => ok(res, await getMyProfile(req.user.id))));

router.patch(
  '/profile',
  validate(profileSchema),
  asyncHandler(async (req, res) => ok(res, await updateMyProfile(req.user.id, req.body)))
);

router.put(
  '/availability',
  validate(availabilitySchema),
  asyncHandler(async (req, res) => ok(res, await updateAvailability(req.user.id, req.body)))
);

router.put(
  '/subjects',
  validate(subjectsSchema),
  asyncHandler(async (req, res) => ok(res, await updateStructuredSubjects(req.user.id, req.body)))
);

router.get(
  '/analytics',
  requireApprovedTutor,
  asyncHandler(async (req, res) => ok(res, await getAnalytics(req.user.id)))
);

// Tutor re-submitted their documents → back to pending for admin review.
router.post(
  '/resubmit',
  asyncHandler(async (req, res) => ok(res, await completeResubmission(req.user.id)))
);

// Multipart image/file uploads. Field name is "file".
router.post(
  '/profile-photo',
  photoUpload.single('file'),
  asyncHandler(async (req, res) => {
    if (!req.file) throw errors.badRequest('No image uploaded (image only, max 8MB).');
    const rel = `uploads/profile_photos/${req.file.filename}`;
    ok(res, await setUserImage(req.user.id, 'profile_picture', rel));
  })
);

router.post(
  '/cover-photo',
  coverUpload.single('file'),
  asyncHandler(async (req, res) => {
    if (!req.file) throw errors.badRequest('No image uploaded (image only, max 8MB).');
    const rel = `uploads/profile_photos/${req.file.filename}`;
    ok(res, await setUserImage(req.user.id, 'cover_photo', rel));
  })
);

// POST /me/documents?type=national_id  (file field "file"; image or PDF)
const changePasswordSchema = z.object({
  currentPassword: z.string().min(1, 'Enter your current password'),
  newPassword: z.string().min(8, 'New password must be at least 8 characters'),
});

router.post(
  '/change-password',
  validate(changePasswordSchema),
  asyncHandler(async (req, res) => ok(res, await changePassword(req.user.id, req.body)))
);

// Tutor enquiry inbox (read-only — no in-app reply).
router.get(
  '/enquiries',
  asyncHandler(async (req, res) => {
    const page = Math.max(1, Number(req.query.page) || 1);
    const limit = Math.min(50, Math.max(1, Number(req.query.limit) || 20));
    ok(res, await getMyEnquiries(req.user.id, { page, limit }));
  })
);

router.post(
  '/enquiries/:id/read',
  asyncHandler(async (req, res) => ok(res, await markEnquiryRead(req.user.id, Number(req.params.id))))
);

router.post(
  '/documents',
  docUpload.single('file'),
  asyncHandler(async (req, res) => {
    if (!req.file) throw errors.badRequest('No file uploaded (image or PDF, max 8MB).');
    const rel = `uploads/documents/${req.file.filename}`;
    ok(
      res,
      await addVerificationDocument(req.user.id, {
        documentType: String(req.query.type || 'document'),
        filePath: rel,
        originalFilename: req.file.originalname,
      })
    );
  })
);

export default router;
