import { query, queryOne } from '../../config/db.js';
import { verifyPassword, hashPassword } from '../../utils/password.js';
import { errors } from '../../utils/http.js';
import { issueTokens } from './tokens.service.js';
import { mediaUrl } from '../../utils/media.js';
import { nowSql, sqlInMinutes } from '../../utils/time.js';
import { sendMail } from '../../utils/mailer.js';

const OTP_TTL_MINUTES = 10;
const genOtp = () => String(Math.floor(100000 + Math.random() * 900000));

function sendOtpEmail(user, code) {
  const name = user.first_name || user.username || 'there';
  return sendMail({
    to: user.email,
    subject: 'Your TutorConnect Malawi verification code',
    html: `
      <h2>Verify your email</h2>
      <p>Hi ${name}, welcome to TutorConnect Malawi.</p>
      <p>Your verification code is:</p>
      <p style="font-size:28px;font-weight:bold;letter-spacing:4px">${code}</p>
      <p>This code expires in ${OTP_TTL_MINUTES} minutes. If you didn't request it, ignore this email.</p>`,
  });
}

/** Public-safe representation of the authenticated user. */
export function presentUser(u) {
  return {
    id: u.id,
    username: u.username,
    email: u.email,
    role: u.role,
    firstName: u.first_name,
    lastName: u.last_name,
    phone: u.phone ?? null,
    district: u.district ?? null,
    profilePicture: mediaUrl(u.profile_picture),
    coverPhoto: mediaUrl(u.cover_photo),
    isVerified: !!u.is_verified,
    tutorStatus: u.tutor_status ?? null,
  };
}

/**
 * Login parity with the website, but open to ALL roles (the mobile app serves
 * customers too, not just trainers/admins). Accepts username or email.
 */
export async function login({ login, password, deviceInfo }) {
  const user = await queryOne(
    `SELECT * FROM users
     WHERE (username = :login OR email = :login) AND deleted_at IS NULL
     LIMIT 1`,
    { login }
  );

  const invalid = errors.unauthorized('Invalid credentials. Please try again.');
  if (!user) throw invalid;

  // Accounts are for tutors and admins only — parents/students don't register
  // (mirrors the website's login, which restricts to trainer/admin roles).
  if (!['trainer', 'admin', 'sub-admin'].includes(user.role)) {
    throw errors.forbidden('This app account is for tutors and administrators only.');
  }

  if (!user.is_verified) {
    throw errors.forbidden('Please verify your email address before logging in.');
  }

  const passwordOk = await verifyPassword(user.password_hash, password);
  if (!passwordOk) throw invalid;

  if (!user.is_active) {
    throw errors.forbidden('Your account is currently inactive. Please contact support.');
  }

  // Determine portal type the same way the website does.
  const uni = await queryOne(
    `SELECT id FROM university_college_tutors
     WHERE user_id = :id OR email = :email OR username = :username
     LIMIT 1`,
    { id: user.id, email: user.email, username: user.username }
  );
  user.portal_type = uni ? 'university' : 'trainer';

  const tokens = await issueTokens(user, deviceInfo);
  return { user: presentUser(user), portalType: user.portal_type, ...tokens };
}

/**
 * Register a new TUTOR account (mirrors the website's tutor sign-up start).
 * Creates a pending, unverified tutor and emails a 6-digit OTP. The tutor
 * completes their profile + is approved by an admin on the web before they
 * become publicly listable.
 */
/**
 * Is an email / username / phone free to use? Mirrors the website's
 * Register::checkAvailability so the app can validate as the user types.
 */
export async function checkAvailability({ email, username, phone }) {
  const out = {};

  if (email) {
    const row = await queryOne(
      `SELECT id FROM users WHERE email = :v AND deleted_at IS NULL LIMIT 1`,
      { v: email.trim().toLowerCase() }
    );
    out.email = { available: !row, message: row ? 'This email is already registered.' : 'Email is available.' };
  }
  if (username) {
    const row = await queryOne(
      `SELECT id FROM users WHERE username = :v AND deleted_at IS NULL LIMIT 1`,
      { v: username.trim() }
    );
    out.username = { available: !row, message: row ? 'This username is already taken.' : 'Username is available.' };
  }
  if (phone) {
    const row = await queryOne(
      `SELECT id FROM users WHERE phone = :v AND role = 'trainer' AND deleted_at IS NULL LIMIT 1`,
      { v: phone.trim() }
    );
    out.phone = { available: !row, message: row ? 'This phone number is already registered.' : 'Phone is available.' };
  }
  return out;
}

export async function register(input) {
  const email = input.email.trim().toLowerCase();
  const username = input.username.trim();
  const phone = input.phone?.trim() || null;

  const existing = await queryOne(
    `SELECT id, email, username FROM users
     WHERE (email = :email OR username = :username) AND deleted_at IS NULL LIMIT 1`,
    { email, username }
  );
  if (existing) {
    const field = existing.email === email ? 'email' : 'username';
    throw errors.conflict(`That ${field} is already registered. Try logging in instead.`);
  }

  // The website also rejects a phone already used by another trainer.
  if (phone) {
    const dupPhone = await queryOne(
      `SELECT id FROM users WHERE phone = :phone AND role = 'trainer' AND deleted_at IS NULL LIMIT 1`,
      { phone }
    );
    if (dupPhone) throw errors.conflict('This phone number is already registered.');
  }

  const passwordHash = await hashPassword(input.password);
  const code = genOtp();
  const now = nowSql();

  const res = await query(
    `INSERT INTO users
       (username, email, password_hash, role, first_name, last_name, phone, gender,
        district, location, is_employed, school_name,
        tutor_status, is_verified, is_active, registration_completed, terms_accepted,
        otp_code, otp_expires_at, created_at, updated_at)
     VALUES
       (:username, :email, :passwordHash, 'trainer', :firstName, :lastName, :phone, :gender,
        :district, :location, :isEmployed, :schoolName,
        'pending', 0, 1, 0, 1,
        :code, :otpExpires, :now, :now)`,
    {
      username,
      email,
      passwordHash,
      firstName: input.firstName,
      lastName: input.lastName,
      phone,
      gender: input.gender ?? null,
      district: input.district ?? null,
      location: input.location ?? null,
      isEmployed: input.isEmployed ? 1 : 0,
      schoolName: input.schoolName ?? null,
      code,
      otpExpires: sqlInMinutes(OTP_TTL_MINUTES),
      now,
    }
  );

  const user = { id: res.insertId, email, username, first_name: input.firstName };

  // Fire-and-forget: SMTP takes ~7s, which blew past the app's request timeout
  // and surfaced as "Network Error". The account already exists at this point;
  // if the mail fails the user can hit "Resend code" on the OTP screen.
  sendOtpEmail(user, code).catch((e) => console.error('[auth] OTP email failed:', e.message));

  return {
    email,
    message: 'Account created. Enter the 6-digit code we emailed you to verify your account.',
  };
}

/** Verify the OTP, mark the account verified, and log the tutor in. */
export async function verifyOtp({ email, code, deviceInfo }) {
  const addr = email.trim().toLowerCase();
  const user = await queryOne(
    `SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1`,
    { email: addr }
  );
  if (!user) throw errors.notFound('No account found for that email.');
  if (user.is_verified) throw errors.badRequest('This account is already verified. Please log in.');
  if (!user.otp_code || String(user.otp_code) !== String(code).trim()) {
    throw errors.badRequest('Incorrect verification code.');
  }
  if (!user.otp_expires_at || new Date(user.otp_expires_at) < new Date(nowSql())) {
    throw errors.badRequest('That code has expired. Request a new one.');
  }

  const now = nowSql();
  await query(
    `UPDATE users SET is_verified = 1, email_verified_at = :now,
       otp_code = NULL, otp_expires_at = NULL, updated_at = :now
     WHERE id = :id`,
    { now, id: user.id }
  );
  user.is_verified = 1;
  user.portal_type = 'trainer';

  const tokens = await issueTokens(user, deviceInfo);
  return { user: presentUser(user), portalType: 'trainer', ...tokens };
}

/** Re-issue and email a fresh OTP for an unverified account. */
export async function resendOtp({ email }) {
  const addr = email.trim().toLowerCase();
  const user = await queryOne(
    `SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1`,
    { email: addr }
  );
  if (!user) throw errors.notFound('No account found for that email.');
  if (user.is_verified) throw errors.badRequest('This account is already verified. Please log in.');

  const code = genOtp();
  await query(
    `UPDATE users SET otp_code = :code, otp_expires_at = :exp, updated_at = :now WHERE id = :id`,
    { code, exp: sqlInMinutes(OTP_TTL_MINUTES), now: nowSql(), id: user.id }
  );
  sendOtpEmail(user, code).catch((e) => console.error('[auth] OTP resend failed:', e.message));
  return { email: addr, message: 'A new verification code has been sent.' };
}

const RESET_TTL_MINUTES = 30;

/**
 * Start a password reset: store a 6-digit code in reset_token (reusing the same
 * columns the website uses) and email it. Always returns success so the endpoint
 * can't be used to probe which emails exist.
 */
export async function requestPasswordReset({ email }) {
  const addr = email.trim().toLowerCase();
  const user = await queryOne(
    `SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1`,
    { email: addr }
  );

  const message = 'If an account exists for that email, a reset code has been sent.';
  if (!user) return { email: addr, message };

  const code = genOtp();
  await query(
    `UPDATE users SET reset_token = :code, reset_expires_at = :exp, updated_at = :now WHERE id = :id`,
    { code, exp: sqlInMinutes(RESET_TTL_MINUTES), now: nowSql(), id: user.id }
  );

  sendMail({
    to: addr,
    subject: 'Reset your TutorConnect Malawi password',
    html: `
      <h2>Password reset</h2>
      <p>Hi ${user.first_name || user.username || 'there'}, we received a request to reset your password.</p>
      <p>Your reset code is:</p>
      <p style="font-size:28px;font-weight:bold;letter-spacing:4px">${code}</p>
      <p>This code expires in ${RESET_TTL_MINUTES} minutes. If you didn't request it, ignore this email — your password stays unchanged.</p>`,
  }).catch((e) => console.error('[auth] reset email failed:', e.message));

  return { email: addr, message };
}

/** Complete a password reset with the emailed code. */
export async function resetPassword({ email, code, newPassword }) {
  const addr = email.trim().toLowerCase();
  const user = await queryOne(
    `SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1`,
    { email: addr }
  );
  if (!user || !user.reset_token || String(user.reset_token) !== String(code).trim()) {
    throw errors.badRequest('Incorrect or expired reset code.');
  }
  if (!user.reset_expires_at || new Date(user.reset_expires_at) < new Date(nowSql())) {
    throw errors.badRequest('That reset code has expired. Request a new one.');
  }

  const passwordHash = await hashPassword(newPassword);
  await query(
    `UPDATE users SET password_hash = :hash, reset_token = NULL, reset_expires_at = NULL, updated_at = :now
     WHERE id = :id`,
    { hash: passwordHash, now: nowSql(), id: user.id }
  );

  // Revoke existing sessions so an attacker with an old token is logged out.
  await query(`UPDATE refresh_tokens SET revoked_at = :now WHERE user_id = :id AND revoked_at IS NULL`, {
    now: nowSql(),
    id: user.id,
  }).catch(() => {});

  return { message: 'Your password has been reset. You can now log in.' };
}

export async function getMe(userId) {
  const user = await queryOne(
    `SELECT * FROM users WHERE id = :id AND deleted_at IS NULL`,
    { id: userId }
  );
  if (!user) throw errors.notFound('User not found');
  return presentUser(user);
}
