import { env } from '../config/env.js';

// DB stores relative paths like "uploads/profile_photos/profile_92.jpg".
// Turn them into absolute URLs the app can load. Leaves full URLs untouched.
export function mediaUrl(path) {
  if (!path) return null;
  if (/^https?:\/\//i.test(path)) return path;
  const base = (env.uploads.publicBaseUrl || '').replace(/\/$/, '');
  const rel = String(path).replace(/^\/+/, '');
  return base ? `${base}/${rel}` : `/${rel}`;
}

export function safeJsonParse(value, fallback) {
  if (value === null || value === undefined || value === '') return fallback;
  if (typeof value === 'object') return value;
  try {
    return JSON.parse(value);
  } catch {
    return fallback;
  }
}
