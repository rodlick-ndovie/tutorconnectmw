import { Router } from 'express';
import authRoutes from './modules/auth/auth.routes.js';
import tutorRoutes from './modules/tutors/tutors.routes.js';
import metaRoutes from './modules/meta/meta.routes.js';
import noticeRoutes from './modules/notices/notices.routes.js';
import notificationRoutes from './modules/notifications/notifications.routes.js';
import meRoutes from './modules/me/me.routes.js';
import resourceRoutes from './modules/resources/resources.routes.js';
import parentRequestRoutes from './modules/parentRequests/parentRequests.routes.js';
import universityRoutes from './modules/university/university.routes.js';
import adminRoutes from './modules/admin/admin.routes.js';
import {
  plansRouter,
  subscriptionRouter,
  paymentsRouter,
} from './modules/subscriptions/subscriptions.routes.js';

// NOTE: parents/students have NO accounts (mirrors the website). The public
// browses tutors and contacts them by WhatsApp/call/email. Accounts exist only
// for tutors and admins. Favorites are stored on-device, so there are no
// customer-account modules (favorites/bookings/chat) mounted here.
const router = Router();

router.get('/health', (req, res) => res.json({ success: true, data: { status: 'ok' } }));

// App version gate + store links. The mobile app compares its own version to
// `minVersion`; if it's older it shows a blocking "update required" screen.
// Bump MIN_APP_VERSION in .env whenever an API change breaks old clients.
router.get('/app-config', (req, res) =>
  res.json({
    success: true,
    data: {
      minVersion: process.env.MIN_APP_VERSION || '0.1.0',
      latestVersion: process.env.LATEST_APP_VERSION || '0.1.0',
      androidStoreUrl: process.env.ANDROID_STORE_URL || '',
      iosStoreUrl: process.env.IOS_STORE_URL || '',
      message: process.env.UPDATE_MESSAGE || '',
    },
  })
);

// Public discovery
router.use('/auth', authRoutes); // tutor/admin login only
router.use('/tutors', tutorRoutes); // search, profile, public reviews, public contact
router.use('/meta', metaRoutes);
router.use('/notices', noticeRoutes);
router.use('/plans', plansRouter);
router.use('/resources', resourceRoutes);
router.use('/parent-requests', parentRequestRoutes);
// University & College support: directory (individuals + firms), registration,
// lecture/service requests, and the university tutor's portal.
router.use('/university', universityRoutes);

// Tutor / admin (authenticated)
router.use('/', notificationRoutes); // /devices, /notifications
router.use('/me', meRoutes);
router.use('/me', subscriptionRouter); // /me/subscription, /me/subscription/checkout
router.use('/payments', paymentsRouter);
router.use('/admin', adminRoutes);

export default router;
