import { z } from 'zod';
import { ok, created } from '../../utils/http.js';
import {
  searchTutors,
  getTutorByIdOrUsername,
  getTutorReviews,
  createReview,
  contactTutor,
  recordProfileView,
  trackContactClick,
} from './tutors.service.js';

export const searchQuerySchema = z.object({
  q: z.string().trim().optional(),
  district: z.string().trim().optional(),
  curriculum: z.string().trim().optional(),
  level: z.string().trim().optional(),
  subject: z.string().trim().optional(),
  teaching_mode: z.string().trim().optional(),
  sort: z.enum(['rating', 'experience', 'reviews']).optional().default('rating'),
  page: z.coerce.number().int().min(1).optional().default(1),
  limit: z.coerce.number().int().min(1).max(50).optional().default(20),
});

const pageSchema = z.object({
  page: z.coerce.number().int().min(1).optional().default(1),
  limit: z.coerce.number().int().min(1).max(50).optional().default(20),
});

export async function listTutors(req, res) {
  const { page, limit, ...filters } = req.query;
  const { items, total } = await searchTutors(filters, { page, limit });
  return ok(res, items, { page, limit, total });
}

export async function getTutor(req, res) {
  const tutor = await getTutorByIdOrUsername(req.params.idOrSlug);
  // Record a unique-by-IP profile view. Fire-and-forget so it never delays or
  // fails the response the user actually asked for.
  recordProfileView(tutor.id, req.ip).catch(() => {});
  return ok(res, tutor);
}

export async function listReviews(req, res) {
  const { page, limit } = pageSchema.parse(req.query);
  const { items, total } = await getTutorReviews(req.params.id, { page, limit });
  return ok(res, items, { page, limit, total });
}

export const reviewSchema = z.object({
  reviewerName: z.string().min(1).max(100),
  reviewerEmail: z.string().email().max(150).optional(),
  rating: z.coerce.number().min(1).max(5),
  comment: z.string().max(1000).optional(),
  isAnonymous: z.coerce.boolean().optional().default(false),
});

export async function postReview(req, res) {
  const review = await createReview(Number(req.params.id), req.body);
  return created(res, review);
}

export const contactSchema = z.object({
  senderName: z.string().min(1).max(100),
  senderEmail: z.string().email().max(150),
  senderPhone: z.string().max(30).optional(),
  subject: z.string().min(1).max(255),
  message: z.string().min(1).max(2000),
  contactPreference: z.string().max(50).optional(),
  contactDetail: z.string().max(100).optional(),
});

export async function postContact(req, res) {
  const result = await contactTutor(Number(req.params.id), req.body, req.ip);
  return created(res, result);
}

export async function postTrackContact(req, res) {
  const result = await trackContactClick(Number(req.params.id), req.body?.method, req.ip);
  return ok(res, result);
}
