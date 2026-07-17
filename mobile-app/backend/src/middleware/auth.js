import { verifyAccessToken } from '../utils/jwt.js';
import { errors } from '../utils/http.js';
import { queryOne } from '../config/db.js';

/** Require a valid access token; attaches req.user (DB row). */
export async function requireAuth(req, res, next) {
  try {
    const header = req.headers.authorization || '';
    const token = header.startsWith('Bearer ') ? header.slice(7) : null;
    if (!token) throw errors.unauthorized('Missing access token');

    const payload = verifyAccessToken(token);
    const user = await queryOne(
      `SELECT id, username, email, role, first_name, last_name, is_active,
              is_verified, tutor_status
       FROM users
       WHERE id = :id AND deleted_at IS NULL`,
      { id: payload.sub }
    );

    if (!user) throw errors.unauthorized('Account no longer exists');
    if (!user.is_active) throw errors.forbidden('Account is inactive');

    req.user = user;
    req.tokenPayload = payload;
    next();
  } catch (err) {
    if (err.name === 'TokenExpiredError') return next(errors.unauthorized('Access token expired'));
    if (err.name === 'JsonWebTokenError') return next(errors.unauthorized('Invalid access token'));
    next(err);
  }
}

/** Require one of the given roles. */
export const requireRole = (...roles) => (req, res, next) => {
  if (!req.user) return next(errors.unauthorized());
  if (!roles.includes(req.user.role)) return next(errors.forbidden('Insufficient permissions'));
  next();
};

/** Require an approved, verified tutor (parity with the website gate). */
export const requireApprovedTutor = (req, res, next) => {
  if (!req.user) return next(errors.unauthorized());
  if (req.user.role !== 'trainer') return next(errors.forbidden('Tutor account required'));
  if (req.user.tutor_status !== 'approved') {
    return next(errors.forbidden('Your tutor account is pending approval'));
  }
  next();
};
