import { Router } from 'express';
import rateLimit from 'express-rate-limit';
import { validate } from '../../middleware/validate.js';
import { asyncHandler } from '../../utils/http.js';
import {
  searchQuerySchema,
  listTutors,
  getTutor,
  listReviews,
  reviewSchema,
  postReview,
  contactSchema,
  postContact,
  postTrackContact,
} from './tutors.controller.js';

const router = Router();

// Light abuse protection for the public write endpoints (no accounts involved).
const writeLimiter = rateLimit({ windowMs: 15 * 60 * 1000, max: 30, standardHeaders: true, legacyHeaders: false });

router.get('/', validate(searchQuerySchema, 'query'), asyncHandler(listTutors));
router.get('/:idOrSlug', asyncHandler(getTutor));
router.get('/:id/reviews', asyncHandler(listReviews));

// Public — parents/students do NOT have accounts (mirrors the website).
router.post('/:id/reviews', writeLimiter, validate(reviewSchema), asyncHandler(postReview));
router.post('/:id/contact', writeLimiter, validate(contactSchema), asyncHandler(postContact));
// Records a WhatsApp/Call/Email tap for analytics (no email, just tracking).
router.post('/:id/track-contact', writeLimiter, asyncHandler(postTrackContact));

export default router;
