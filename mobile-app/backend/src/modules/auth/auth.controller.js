import { z } from 'zod';
import { ok, created } from '../../utils/http.js';
import {
  login,
  register,
  verifyOtp,
  resendOtp,
  checkAvailability,
  requestPasswordReset,
  resetPassword,
  getMe,
} from './auth.service.js';
import { rotateTokens, revokeRefreshToken } from './tokens.service.js';

export const loginSchema = z.object({
  login: z.string().min(1, 'Username or email is required'),
  password: z.string().min(1, 'Password is required'),
  deviceInfo: z.string().max(255).optional(),
});

// Fields mirror the website's two-step tutor sign-up
// (step 1 = personal details, step 2 = account credentials).
export const registerSchema = z.object({
  // Step 1
  firstName: z.string().min(2, 'First name is required').max(50),
  lastName: z.string().min(2, 'Last name is required').max(50),
  email: z.string().email('Enter a valid email').max(100),
  phone: z.string().min(1, 'Phone is required').max(20),
  gender: z.enum(['Male', 'Female', 'Other', 'Prefer not to say']).optional(),
  district: z.string().max(50).optional(),
  location: z.string().max(100).optional(),
  isEmployed: z.boolean().optional(),
  schoolName: z.string().max(100).optional(),
  // Step 2
  username: z.string().min(3, 'Username must be at least 3 characters').max(20)
    .regex(/^[a-zA-Z0-9_.]+$/, 'Use letters, numbers, dot or underscore only'),
  password: z.string().min(8, 'Password must be at least 8 characters').max(100),
});

export const availabilitySchema = z.object({
  email: z.string().optional(),
  username: z.string().optional(),
  phone: z.string().optional(),
});

export const verifyOtpSchema = z.object({
  email: z.string().email(),
  code: z.string().min(4).max(8),
  deviceInfo: z.string().max(255).optional(),
});

export const resendOtpSchema = z.object({ email: z.string().email() });

export const forgotPasswordSchema = z.object({ email: z.string().email() });

export const resetPasswordSchema = z.object({
  email: z.string().email(),
  code: z.string().min(4).max(8),
  newPassword: z.string().min(8, 'Password must be at least 8 characters').max(100),
});

export const refreshSchema = z.object({
  refreshToken: z.string().min(10),
});

export async function postLogin(req, res) {
  const result = await login(req.body);
  return ok(res, result);
}

export async function postRegister(req, res) {
  return created(res, await register(req.body));
}

export async function postVerifyOtp(req, res) {
  return ok(res, await verifyOtp(req.body));
}

export async function postResendOtp(req, res) {
  return ok(res, await resendOtp(req.body));
}

export async function getAvailability(req, res) {
  return ok(res, await checkAvailability(req.query));
}

export async function postForgotPassword(req, res) {
  return ok(res, await requestPasswordReset(req.body));
}

export async function postResetPassword(req, res) {
  return ok(res, await resetPassword(req.body));
}

export async function postRefresh(req, res) {
  const tokens = await rotateTokens(req.body.refreshToken);
  return ok(res, tokens);
}

export async function postLogout(req, res) {
  if (req.body?.refreshToken) await revokeRefreshToken(req.body.refreshToken);
  return ok(res, { loggedOut: true });
}

export async function getProfile(req, res) {
  return ok(res, await getMe(req.user.id));
}
