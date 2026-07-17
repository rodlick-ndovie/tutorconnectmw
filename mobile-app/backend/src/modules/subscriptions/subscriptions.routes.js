import { Router } from 'express';
import { z } from 'zod';
import { requireAuth, requireRole } from '../../middleware/auth.js';
import { validate } from '../../middleware/validate.js';
import { ok, created, asyncHandler } from '../../utils/http.js';
import { listPlans, getMySubscription, checkout, syncPayment } from './subscriptions.service.js';

// Mounted at /plans, /me/subscription* and /payments — split across two routers.
export const plansRouter = Router();
plansRouter.get(
  '/',
  asyncHandler(async (req, res) => ok(res, await listPlans()))
);

export const subscriptionRouter = Router();
subscriptionRouter.use(requireAuth);

subscriptionRouter.get(
  '/subscription',
  asyncHandler(async (req, res) => ok(res, await getMySubscription(req.user.id)))
);

const checkoutSchema = z.object({
  planId: z.coerce.number().int().positive(),
  billingMonths: z.coerce.number().int().min(1).max(12).default(1),
});

subscriptionRouter.post(
  '/subscription/checkout',
  requireRole('trainer'),
  validate(checkoutSchema),
  asyncHandler(async (req, res) => created(res, await checkout(req.user, req.body)))
);

export const paymentsRouter = Router();

// Server-to-server callback from PayChangu (no auth; verified against the API).
paymentsRouter.post(
  '/paychangu/callback',
  asyncHandler(async (req, res) => {
    const txRef =
      req.body?.tx_ref || req.body?.data?.tx_ref || req.body?.event?.tx_ref || req.query?.tx_ref;
    if (!txRef) return res.status(400).json({ success: false, error: { code: 'BAD_REQUEST', message: 'Missing tx_ref' } });
    const result = await syncPayment(String(txRef));
    return ok(res, result);
  })
);

// PayChangu redirects the browser here when the hosted checkout finishes. The
// mobile WebView intercepts this URL and verifies server-side, so this page is
// a graceful fallback (and stops the redirect from 404ing). It also fires the
// app deep link in case the checkout was opened in an external browser.
paymentsRouter.get('/return', (req, res) => {
  const txRef = String(req.query.tx_ref || req.query.txRef || '');
  res
    .set('Content-Type', 'text/html; charset=utf-8')
    .set('Cache-Control', 'no-store')
    .send(`<!doctype html><html><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payment complete</title>
<style>body{font-family:-apple-system,Segoe UI,Roboto,sans-serif;background:#FDECE0;color:#2C3E50;
display:flex;min-height:100vh;margin:0;align-items:center;justify-content:center;text-align:center}
.card{background:#fff;padding:32px 28px;border-radius:16px;max-width:340px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
h1{font-size:20px;margin:12px 0 6px}p{color:#5b6b7a;font-size:14px;line-height:1.5;margin:0 0 20px}
a{display:inline-block;background:#E55C0D;color:#fff;text-decoration:none;font-weight:700;
padding:12px 22px;border-radius:999px}.tick{font-size:44px}</style></head>
<body><div class="card"><div class="tick">✓</div><h1>Payment received</h1>
<p>You can return to the TutorConnect app. Your subscription will activate once confirmed.</p>
<a href="tutorconnect://payments/return?tx_ref=${encodeURIComponent(txRef)}">Return to the app</a></div>
<script>setTimeout(function(){location.href="tutorconnect://payments/return?tx_ref=${encodeURIComponent(txRef)}"},600);</script>
</body></html>`);
});

// Client polls this after returning from the checkout.
paymentsRouter.get(
  '/:txRef/status',
  requireAuth,
  asyncHandler(async (req, res) => ok(res, await syncPayment(req.params.txRef)))
);

export default { plansRouter, subscriptionRouter, paymentsRouter };
