// Runs the additive .sql migration files in migrations/ against the configured DB.
// Safe to re-run: every statement uses CREATE TABLE IF NOT EXISTS.
import { readFileSync, readdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import mysql from 'mysql2/promise';
import { env } from '../src/config/env.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const migrationsDir = path.join(__dirname, '..', 'migrations');

async function run() {
  const conn = await mysql.createConnection({
    host: env.db.host,
    port: env.db.port,
    user: env.db.user,
    password: env.db.password,
    database: env.db.database,
    multipleStatements: true,
  });

  const files = readdirSync(migrationsDir).filter((f) => f.endsWith('.sql')).sort();
  for (const file of files) {
    const sql = readFileSync(path.join(migrationsDir, file), 'utf8');
    process.stdout.write(`Applying ${file} ... `);
    await conn.query(sql);
    console.log('done');
  }

  await conn.end();
  console.log(`\nMigrations complete (${files.length} file(s)).`);
}

run().catch((err) => {
  console.error('Migration failed:', err.message);
  process.exit(1);
});
