import { query } from '../../config/db.js';
import { nowSql } from '../../utils/time.js';
import { sendExpoPush } from '../../utils/push.js';
import { safeJsonParse } from '../../utils/media.js';

/**
 * Persist an in-app notification AND fan it out to the user's devices.
 * Call this from any module (new booking, message, status change, etc.).
 */
export async function notify(userId, { type, title, body, data = null }) {
  const now = nowSql();
  await query(
    `INSERT INTO notifications (user_id, type, title, body, data, created_at)
     VALUES (:uid, :type, :title, :body, :data, :now)`,
    { uid: userId, type, title, body: body ?? null, data: data ? JSON.stringify(data) : null, now }
  );

  const tokens = await query(
    `SELECT expo_push_token FROM device_tokens WHERE user_id = :uid`,
    { uid: userId }
  );
  await sendExpoPush(tokens.map((t) => t.expo_push_token), { title, body, data: { type, ...data } });
}

export async function registerDevice(userId, { expoPushToken, platform }) {
  const now = nowSql();
  await query(
    `INSERT INTO device_tokens (user_id, expo_push_token, platform, created_at, updated_at)
     VALUES (:uid, :token, :platform, :now, :now)
     ON DUPLICATE KEY UPDATE user_id = :uid, platform = :platform, updated_at = :now`,
    { uid: userId, token: expoPushToken, platform: platform ?? null, now }
  );
  return { registered: true };
}

export async function removeDevice(token) {
  await query(`DELETE FROM device_tokens WHERE expo_push_token = :token`, { token });
  return { removed: true };
}

export async function listNotifications(userId, { page, limit }) {
  const offset = (page - 1) * limit;
  const items = await query(
    `SELECT id, type, title, body, data, read_at, created_at
     FROM notifications WHERE user_id = :uid
     ORDER BY created_at DESC LIMIT :limit OFFSET :offset`,
    { uid: userId, limit, offset }
  );
  const [{ total }] = await query(
    `SELECT COUNT(*) AS total FROM notifications WHERE user_id = :uid`,
    { uid: userId }
  );
  const [{ unread }] = await query(
    `SELECT COUNT(*) AS unread FROM notifications WHERE user_id = :uid AND read_at IS NULL`,
    { uid: userId }
  );
  return {
    items: items.map((n) => ({
      id: n.id,
      type: n.type,
      title: n.title,
      body: n.body,
      data: safeJsonParse(n.data, null),
      read: !!n.read_at,
      createdAt: n.created_at,
    })),
    total: Number(total),
    unread: Number(unread),
  };
}

export async function markNotificationRead(userId, id) {
  await query(
    `UPDATE notifications SET read_at = :now WHERE id = :id AND user_id = :uid AND read_at IS NULL`,
    { now: nowSql(), id, uid: userId }
  );
  return { id, read: true };
}

export async function markAllRead(userId) {
  await query(
    `UPDATE notifications SET read_at = :now WHERE user_id = :uid AND read_at IS NULL`,
    { now: nowSql(), uid: userId }
  );
  return { read: true };
}
