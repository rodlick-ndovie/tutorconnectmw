import { Router } from 'express';
import rateLimit from 'express-rate-limit';
import { validate } from '../../middleware/validate.js';
import { requireAuth } from '../../middleware/auth.js';
import { asyncHandler } from '../../utils/http.js';
import {
  loginSchema,
  registerSchema,
  verifyOtpSchema,
  resendOtpSchema,
  availabilitySchema,
  forgotPasswordSchema,
  resetPasswordSchema,
  refreshSchema,
  postLogin,
  postRegister,
  postVerifyOtp,
  postResendOtp,
  getAvailability,
  postForgotPassword,
  postResetPassword,
  postRefresh,
  postLogout,
  getProfile,
} from './auth.controller.js';

const router = Router();

const loginLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 20,
  standardHeaders: true,
  legacyHeaders: false,
  message: { success: false, error: { code: 'RATE_LIMITED', message: 'Too many attempts. Try again later.' } },
});

const registerLimiter = rateLimit({
  windowMs: 60 * 60 * 1000,
  max: 10,
  standardHeaders: true,
  legacyHeaders: false,
  message: { success: false, error: { code: 'RATE_LIMITED', message: 'Too many attempts. Try again later.' } },
});

router.post('/login', loginLimiter, validate(loginSchema), asyncHandler(postLogin));
router.post('/register', registerLimiter, validate(registerSchema), asyncHandler(postRegister));
router.post('/verify-otp', registerLimiter, validate(verifyOtpSchema), asyncHandler(postVerifyOtp));
router.post('/resend-otp', registerLimiter, validate(resendOtpSchema), asyncHandler(postResendOtp));
router.post('/forgot-password', registerLimiter, validate(forgotPasswordSchema), asyncHandler(postForgotPassword));
router.post('/reset-password', registerLimiter, validate(resetPasswordSchema), asyncHandler(postResetPassword));
// Live email/username/phone availability while the sign-up form is filled in.
router.get('/check-availability', validate(availabilitySchema, 'query'), asyncHandler(getAvailability));
router.post('/refresh', validate(refreshSchema), asyncHandler(postRefresh));
router.post('/logout', asyncHandler(postLogout));
router.get('/me', requireAuth, asyncHandler(getProfile));

export default router;
