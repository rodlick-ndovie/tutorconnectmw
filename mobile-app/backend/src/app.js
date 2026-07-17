import fs from 'node:fs';
import express from 'express';
import helmet from 'helmet';
import cors from 'cors';
import morgan from 'morgan';
import rateLimit from 'express-rate-limit';
import { env } from './config/env.js';
import routes from './routes.js';
import { notFoundHandler, errorHandler } from './middleware/error.js';

export function createApp() {
  const app = express();

  app.set('trust proxy', 1);
  // Allow the mobile app (a different origin) to load images served here.
  app.use(helmet({ crossOriginResourcePolicy: { policy: 'cross-origin' } }));

  // Serve uploaded media through the API so the app loads it from the same
  // reachable host as the API. DB stores paths like "uploads/profile_photos/x.jpg",
  // so mounting at /uploads maps them 1:1 when PUBLIC_BASE_URL is the API's base.
  // absDir is anchored to the backend root (not cwd) — the files always live in
  // the backend and serve from the exact folder multer writes to, so images are
  // never empty because of a wrong working directory on cPanel.
  const uploadsPath = env.uploads.absDir;
  fs.mkdirSync(uploadsPath, { recursive: true }); // ensure it exists to serve from
  app.use(
    '/uploads',
    express.static(uploadsPath, { maxAge: '7d', fallthrough: true })
  );
  app.use(
    cors({
      origin: env.corsOrigins.length ? env.corsOrigins : true,
      credentials: true,
    })
  );
  app.use(express.json({ limit: '1mb' }));
  app.use(express.urlencoded({ extended: true }));
  app.use(morgan(env.isProd ? 'combined' : 'dev'));

  app.use(
    rateLimit({
      windowMs: 60 * 1000,
      max: 120,
      standardHeaders: true,
      legacyHeaders: false,
    })
  );

  // API responses are dynamic: never let a client serve a cached/304 body.
  // (Express's default ETag made repeat /auth/check-availability calls return a
  // bodyless 304, which the app read as a failure.) Static /uploads above keeps
  // its own caching.
  app.set('etag', false);
  app.use(env.apiPrefix, (req, res, next) => {
    res.set('Cache-Control', 'no-store');
    next();
  }, routes);

  app.use(notFoundHandler);
  app.use(errorHandler);

  return app;
}
