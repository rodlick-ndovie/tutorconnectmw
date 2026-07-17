import crypto from 'node:crypto';
import { pool, query, queryOne } from '../../config/db.js';
import { signAccessToken, signRefreshToken, verifyRefreshToken } from '../../utils/jwt.js';
import { errors } from '../../utils/http.js';
import { env } from '../../config/env.js';

function hashToken(token) {
  return crypto.createHash('sha256').update(token).digest('hex');
}

function refreshExpiry() {
  // Mirror JWT_REFRESH_TTL (default 30d) for the DB row.
  const days = /^(\d+)d$/.exec(env.jwt.refreshTtl)?.[1] ?? 30;
  const d = new Date();
  d.setDate(d.getDate() + Number(days));
  return d.toISOString().slice(0, 19).replace('T', ' ');
}

/** Issue an access + refresh token pair and persist the refresh token hash. */
export async function issueTokens(user, deviceInfo = null) {
  const tokenId = crypto.randomUUID();
  const refreshToken = signRefreshToken(user, tokenId);

  await query(
    `INSERT INTO refresh_tokens (id, user_id, token_hash, expires_at, device_info, created_at)
     VALUES (:id, :userId, :hash, :expiresAt, :device, NOW())`,
    {
      id: tokenId,
      userId: user.id,
      hash: hashToken(refreshToken),
      expiresAt: refreshExpiry(),
      device: deviceInfo,
    }
  );

  return { accessToken: signAccessToken(user), refreshToken };
}

/**
 * How long after rotation the old refresh token still works.
 *
 * Rotation is not atomic across the network: we can revoke the old token, issue
 * a new pair, and then have the response lost (dropped Wi-Fi, timeout). The app
 * still holds the old token and retries — which looks exactly like reuse, and
 * used to revoke the whole family and log the user out. On a flaky mobile
 * connection that happened often. Inside this window we treat a repeat as the
 * lost-response retry it almost certainly is; outside it, it's still treated as
 * a stolen-token replay and the family is revoked.
 */
const ROTATION_GRACE_SECONDS = 60;

/** Rotate a refresh token: validate, revoke the old one, issue a new pair. */
export async function rotateTokens(refreshToken) {
  let payload;
  try {
    payload = verifyRefreshToken(refreshToken);
  } catch {
    throw errors.unauthorized('Invalid refresh token');
  }

  const row = await queryOne(
    `SELECT *, TIMESTAMPDIFF(SECOND, revoked_at, NOW()) AS revoked_secs_ago
       FROM refresh_tokens WHERE id = :id`,
    { id: payload.jti }
  );

  const withinGrace =
    row?.revoked_at && row.revoked_secs_ago !== null && row.revoked_secs_ago <= ROTATION_GRACE_SECONDS;

  if (!row || (row.revoked_at && !withinGrace)) {
    // Genuine reuse of a long-revoked token => revoke the whole family.
    await query(`UPDATE refresh_tokens SET revoked_at = NOW() WHERE user_id = :uid AND revoked_at IS NULL`, {
      uid: payload.sub,
    });
    throw errors.unauthorized('Refresh token reuse detected');
  }

  if (row.token_hash !== hashToken(refreshToken)) {
    throw errors.unauthorized('Refresh token mismatch');
  }

  const user = await queryOne(
    `SELECT id, role, is_active FROM users WHERE id = :id AND deleted_at IS NULL`,
    { id: payload.sub }
  );
  if (!user || !user.is_active) throw errors.unauthorized('Account unavailable');

  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();
    // `revoked_at IS NULL` keeps a grace-window retry from resetting the clock
    // and extending its own grace period indefinitely.
    await conn.execute(
      `UPDATE refresh_tokens SET revoked_at = NOW() WHERE id = :id AND revoked_at IS NULL`,
      { id: row.id }
    );
    await conn.commit();
  } catch (e) {
    await conn.rollback();
    throw e;
  } finally {
    conn.release();
  }

  return issueTokens(user, row.device_info);
}

export async function revokeRefreshToken(refreshToken) {
  try {
    const payload = verifyRefreshToken(refreshToken);
    await query(`UPDATE refresh_tokens SET revoked_at = NOW() WHERE id = :id`, { id: payload.jti });
  } catch {
    // already invalid; nothing to do
  }
}
