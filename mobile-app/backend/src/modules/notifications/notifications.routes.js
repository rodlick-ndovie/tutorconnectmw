import { Router } from 'express';
import { z } from 'zod';
import { requireAuth } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { ok, created, asyncHandler } from '../../utils/http.js';
import {
  registerDevice,
  removeDevice,
  listNotifications,
  markNotificationRead,
  markAllRead,
} from './notifications.service.js';

// NOTE: this router is mounted at '/', so it sees every request. We must NOT
// apply requireAuth globally here (that would block sibling public routes like
// /plans). Auth is attached per-route instead; unmatched paths fall through.
const router = Router();

const deviceSchema = z.object({
  expoPushToken: z.string().min(1).max(255),
  platform: z.enum(['ios', 'android', 'web']).optional(),
});

const pageSchema = z.object({
  page: z.coerce.number().int().min(1).default(1),
  limit: z.coerce.number().int().min(1).max(50).default(20),
});

router.post(
  '/devices',
  requireAuth,
  validate(deviceSchema),
  asyncHandler(async (req, res) => created(res, await registerDevice(req.user.id, req.body)))
);

router.delete(
  '/devices/:token',
  requireAuth,
  asyncHandler(async (req, res) => ok(res, await removeDevice(req.params.token)))
);

router.get(
  '/notifications',
  requireAuth,
  validate(pageSchema, 'query'),
  asyncHandler(async (req, res) => {
    const { page, limit } = req.query;
    const { items, total, unread } = await listNotifications(req.user.id, { page, limit });
    return ok(res, items, { page, limit, total, unread });
  })
);

router.patch(
  '/notifications/read-all',
  requireAuth,
  asyncHandler(async (req, res) => ok(res, await markAllRead(req.user.id)))
);

router.patch(
  '/notifications/:id/read',
  requireAuth,
  asyncHandler(async (req, res) => ok(res, await markNotificationRead(req.user.id, Number(req.params.id))))
);

export default router;
