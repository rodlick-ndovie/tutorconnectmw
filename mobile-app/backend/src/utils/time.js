// CodeIgniter writes MySQL DATETIME as 'YYYY-MM-DD HH:mm:ss' in the app
// timezone. We mirror that exactly so rows written by the API are identical
// to ones written by the PHP site.
import { env } from '../config/env.js';

function offsetMinutes() {
  const m = /^([+-])(\d{2}):(\d{2})$/.exec(env.db.timezone || '+02:00');
  const sign = m && m[1] === '-' ? -1 : 1;
  return m ? sign * (Number(m[2]) * 60 + Number(m[3])) : 120;
}

export function nowSql() {
  // Convert "now" into the configured DB timezone offset (default +02:00).
  const d = new Date(Date.now() + offsetMinutes() * 60 * 1000);
  return d.toISOString().slice(0, 19).replace('T', ' ');
}

/** A DATETIME string `minutes` from now, in the DB timezone. */
export function sqlInMinutes(minutes) {
  const d = new Date(Date.now() + (offsetMinutes() + minutes) * 60 * 1000);
  return d.toISOString().slice(0, 19).replace('T', ' ');
}
