import os from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import dotenv from 'dotenv';

dotenv.config();

// The backend project root, derived from THIS file's location (src/config/env.js
// -> up two levels). Used to anchor the uploads folder so it never depends on
// process.cwd(), which Passenger/cPanel does not guarantee to be the app root.
const backendRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '../../');

function required(name, fallback = undefined) {
  const value = process.env[name] ?? fallback;
  if (value === undefined) {
    throw new Error(`Missing required environment variable: ${name}`);
  }
  return value;
}

/**
 * Best-effort LAN IPv4 for this machine, so media/callback URLs work on a phone
 * without hardcoding an address that changes with the network. Virtual adapters
 * (Hyper-V, VirtualBox, Docker…) are skipped and private ranges preferred.
 */
function detectLanIp() {
  const skip = /(vethernet|virtualbox|vmware|docker|loopback|hyper-v|wsl)/i;
  const candidates = [];
  for (const [name, list] of Object.entries(os.networkInterfaces())) {
    if (skip.test(name)) continue;
    for (const net of list ?? []) {
      if (net.family === 'IPv4' && !net.internal) candidates.push(net.address);
    }
  }
  const private4 = candidates.find((a) =>
    /^(192\.168\.|10\.|172\.(1[6-9]|2\d|3[01])\.)/.test(a)
  );
  return private4 || candidates[0] || 'localhost';
}

const port = Number(process.env.PORT || 4000);
const apiPrefix = process.env.API_PREFIX || '/api/v1';
const lanHost = detectLanIp();
// Base URL other devices (the phone) can actually reach this API on.
const autoBaseUrl = `http://${lanHost}:${port}`;

export const env = {
  lanHost,
  autoBaseUrl,
  nodeEnv: process.env.NODE_ENV || 'development',
  isProd: (process.env.NODE_ENV || 'development') === 'production',
  port,
  apiPrefix,

  db: {
    host: process.env.DB_HOST || '127.0.0.1',
    port: Number(process.env.DB_PORT || 3306),
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'tutorconnectmw',
    connectionLimit: Number(process.env.DB_CONNECTION_LIMIT || 10),
    timezone: process.env.DB_TIMEZONE || '+02:00',
  },

  jwt: {
    accessSecret: required('JWT_ACCESS_SECRET', 'dev-access-secret'),
    refreshSecret: required('JWT_REFRESH_SECRET', 'dev-refresh-secret'),
    accessTtl: process.env.JWT_ACCESS_TTL || '15m',
    refreshTtl: process.env.JWT_REFRESH_TTL || '30d',
  },

  uploads: {
    dir: process.env.UPLOADS_DIR || '../../website/public/uploads',
    // Absolute uploads path, resolved against the backend root (NOT cwd). This
    // is where files are written AND served from, so both always agree and the
    // documents/images live inside the backend — no empty images from a wrong
    // working directory on cPanel/Passenger. Absolute UPLOADS_DIR is used as-is.
    absDir: path.isAbsolute(process.env.UPLOADS_DIR || '')
      ? process.env.UPLOADS_DIR
      : path.resolve(backendRoot, process.env.UPLOADS_DIR || '../../website/public/uploads'),
    // Defaults to this machine's LAN address so images load on a real device
    // without editing .env when the network changes. Set PUBLIC_BASE_URL in
    // production to the real public API origin.
    publicBaseUrl: process.env.PUBLIC_BASE_URL || autoBaseUrl,
  },

  corsOrigins: (process.env.CORS_ORIGINS || '')
    .split(',')
    .map((s) => s.trim())
    .filter(Boolean),

  paychangu: {
    publicKey: process.env.PAYCHANGU_PUBLIC_KEY || '',
    secretKey: process.env.PAYCHANGU_SECRET_KEY || '',
    // Where PayChangu sends the server-to-server callback and the user return.
    // Default to the auto-detected LAN base (the app intercepts the return URL
    // in its WebView, so this works in dev without a public tunnel).
    callbackUrl:
      process.env.PAYCHANGU_CALLBACK_URL || `${autoBaseUrl}${apiPrefix}/payments/paychangu/callback`,
    returnUrl: process.env.PAYCHANGU_RETURN_URL || `${autoBaseUrl}${apiPrefix}/payments/return`,
  },
};
