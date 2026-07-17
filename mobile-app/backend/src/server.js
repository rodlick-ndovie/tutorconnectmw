import { createApp } from './app.js';
import { env } from './config/env.js';
import { ping, pool } from './config/db.js';

async function start() {
  try {
    await ping();
    console.log(`[db] connected to ${env.db.database}@${env.db.host}:${env.db.port}`);
  } catch (err) {
    console.error('[db] connection FAILED:', err.message);
    // In local dev, fail fast. But under cPanel/Passenger, exiting before the
    // app calls listen() makes Passenger serve a generic HTML error page (and
    // cPanel's post-install check fails on the content-type change). In
    // production we start anyway so /health works and the DB error is logged
    // and surfaced per-request — far easier to diagnose than a crash loop.
    if (process.env.NODE_ENV !== 'production') process.exit(1);
  }

  const app = createApp();
  // Passenger passes the listen target via PORT (sometimes a socket path, not a
  // number), and it hijacks listen() to bind its own socket — so use the RAW
  // PORT and don't force a host. With no host, Node still listens on all
  // interfaces, so phones on the LAN can reach it in local dev too.
  const port = process.env.PORT || env.port;
  const server = app.listen(port, () => {
    console.log(`[api] listening on port ${port} (prefix ${env.apiPrefix})`);
    console.log(`[api] media   ${env.uploads.publicBaseUrl}/uploads/...`);
  });

  const shutdown = async (signal) => {
    console.log(`\n[api] ${signal} received, shutting down...`);
    server.close(async () => {
      await pool.end();
      process.exit(0);
    });
  };
  process.on('SIGINT', () => shutdown('SIGINT'));
  process.on('SIGTERM', () => shutdown('SIGTERM'));
}

start();
