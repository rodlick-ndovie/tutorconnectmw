import { env } from './env.js';

// PayChangu exposes a SINGLE API host for both test and live — the mode is
// decided by the API key (SEC-TEST-… vs SEC-…), and a test key returns a
// test-checkout URL from the same host. The website's PayChangu.php pointed
// test keys at `api-test.paychangu.com`, which does not exist (NXDOMAIN), so
// every checkout threw "fetch failed". Always use the real host.
const baseUrl = 'https://api.paychangu.com';

export const paychanguConfigured = () =>
  Boolean(env.paychangu.publicKey && env.paychangu.secretKey);

async function makeRequest(method, endpoint, body) {
  const res = await fetch(`${baseUrl}${endpoint}`, {
    method,
    headers: {
      Authorization: `Bearer ${env.paychangu.secretKey}`,
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: body ? JSON.stringify(body) : undefined,
  });
  try {
    return await res.json();
  } catch {
    return { status: 'error', message: 'Invalid response', status_code: res.status };
  }
}

export async function initializePayment(data) {
  // POST /payment creates a hosted checkout session. (/payment/initialize is a
  // GET-only route and 405s on POST — another bug copied from the PHP library.)
  return makeRequest('POST', '/payment', {
    amount: data.amount,
    currency: data.currency ?? 'MWK',
    email: data.email,
    first_name: data.firstName,
    last_name: data.lastName,
    callback_url: data.callbackUrl,
    return_url: data.returnUrl,
    tx_ref: data.txRef,
    customization: {
      title: data.title ?? 'TutorConnect Malawi Subscription',
      description: data.description ?? 'Subscription payment for educational services',
    },
  });
}

export async function verifyPayment(txRef) {
  const ref = String(txRef || '').trim();
  if (!ref) return { status: 'error', message: 'Missing transaction reference.' };

  // GET /verify-payment/{tx_ref} is the one that works; the other paths the PHP
  // library tried return 500/404 on this host.
  const result = await makeRequest('GET', `/verify-payment/${encodeURIComponent(ref)}`).catch(() => null);
  return result && result.status === 'success' ? result : null;
}

export function isSuccessfulVerification(result, { txRef = '', currency = '', amount = null } = {}) {
  if (!result || String(result.status || '').toLowerCase() !== 'success') return false;
  const data = result.data;
  if (!data || typeof data !== 'object') return false;

  const paymentStatus = String(data.status || '').toLowerCase();
  if (!['success', 'successful', 'completed', 'paid'].includes(paymentStatus)) return false;
  if (txRef && String(data.tx_ref || '') !== txRef) return false;
  if (currency && String(data.currency || '').toUpperCase() !== currency.toUpperCase()) return false;
  if (amount !== null && Math.abs(Number(data.amount || 0) - amount) > 0.01) return false;
  return true;
}

export const paychanguPublicKey = () => env.paychangu.publicKey;
