import mysql from 'mysql2/promise';
import { env } from './env.js';

// Single shared pool. We talk to the SAME database the PHP site uses,
// so all writes must match CodeIgniter conventions (datetime strings,
// soft deletes via deleted_at, JSON-in-TEXT columns).
export const pool = mysql.createPool({
  host: env.db.host,
  port: env.db.port,
  user: env.db.user,
  password: env.db.password,
  database: env.db.database,
  connectionLimit: env.db.connectionLimit,
  timezone: env.db.timezone,
  waitForConnections: true,
  namedPlaceholders: true,
  dateStrings: true, // keep DATETIME as 'YYYY-MM-DD HH:mm:ss' strings
});

/**
 * Run a query and return rows. We use pool.query (text protocol) rather than
 * execute (binary prepared) because the prepared protocol rejects bound
 * LIMIT/OFFSET params ("Incorrect arguments to mysqld_stmt_execute"). Named
 * placeholders are still escaped by mysql2, so this remains injection-safe.
 */
export async function query(sql, params = {}) {
  const [rows] = await pool.query(sql, params);
  return rows;
}

/** Run a query and return the first row (or null). */
export async function queryOne(sql, params = {}) {
  const rows = await query(sql, params);
  return rows[0] ?? null;
}

export async function ping() {
  const conn = await pool.getConnection();
  try {
    await conn.ping();
  } finally {
    conn.release();
  }
}
