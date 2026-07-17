# TutorConnect API (mobile backend)

Node.js/Express REST API for the TutorConnect Malawi mobile app. It connects to
the **existing** MySQL database used by the PHP (CodeIgniter 4) website and adds
only a few API-owned tables — the live site is unaffected.

See [`../IMPLEMENTATION_PLAN.md`](../IMPLEMENTATION_PLAN.md) for the full roadmap.

## Model (matches the website)
- **Parents/students have NO accounts.** They browse tutors and contact them by
  WhatsApp / call / email, and can leave reviews — all without logging in.
- **Accounts are for tutors and admins only** (login is restricted to those roles).
- There is **no in-app booking or chat**; "contact" emails the tutor and records
  a contact click, exactly like the website's `Home::sendMessage`.
- Favorites are stored **on the device**, not on the server.

## Requirements
- Node.js 20+
- MySQL (the running `tutorconnectmw` database — point at a **copy** while testing writes)

## Setup
```bash
cd mobile-app/backend
cp .env.example .env        # then edit DB + JWT secrets
npm install
npm run migrate             # creates additive tables (favorites, device_tokens, refresh_tokens, notifications)
npm run dev                 # starts http://localhost:4000/api/v1
```

## Endpoints
**Public (no account)**
| Method | Endpoint | Notes |
|---|---|---|
| GET | `/api/v1/health` | liveness |
| GET | `/api/v1/tutors` | search + filters + pagination (subscription-aware ranking) |
| GET | `/api/v1/tutors/:idOrSlug` | full tutor profile (contact fields gated by tutor's plan/visibility) |
| GET | `/api/v1/tutors/:id/reviews` | paginated reviews |
| POST | `/api/v1/tutors/:id/reviews` | public review (reviewerName + rating) → recomputes aggregate rating |
| POST | `/api/v1/tutors/:id/contact` | public contact → emails the tutor + records a contact click |
| GET | `/api/v1/meta/districts\|curricula\|levels\|subjects` | filter options |
| GET | `/api/v1/notices[/:id]` | approved notices feed ("Success Stories") |
| GET | `/api/v1/plans` | subscription plans |
| GET | `/api/v1/resources/past-papers[/:id]`, `/resources/videos[/:id]` | resources |
| GET/POST | `/api/v1/parent-requests[/:id]` | request board (apply requires tutor auth) |

**Tutor / admin (authenticated)**
| Method | Endpoint | Notes |
|---|---|---|
| POST | `/api/v1/auth/login` | username **or** email + password (argon2id; tutors/admins only) |
| POST | `/api/v1/auth/refresh` \| `/auth/logout` | token rotation / revoke |
| GET | `/api/v1/auth/me` | current user |
| GET/PATCH | `/api/v1/me/profile` | tutor self-service profile |
| PUT | `/api/v1/me/availability`, `/me/subjects` | availability + structured subjects JSON |
| GET | `/api/v1/me/analytics` | tutor dashboard data |
| GET | `/api/v1/me/subscription` · POST `/me/subscription/checkout` | subscription + PayChangu |
| POST/GET | `/api/v1/payments/paychangu/callback`, `/payments/:txRef/status` | verify + activate |
| POST/DELETE | `/api/v1/devices[/:token]` | register/remove Expo push token |
| GET/PATCH | `/api/v1/notifications[/:id/read]`, `/read-all` | in-app notification feed |
| GET/POST | `/api/v1/admin/overview`, `/admin/tutors`, `/admin/payments`, approvals | admin subset |

## Conventions (keep parity with the website)
- Passwords use **argon2id**; the API verifies existing PHP hashes and writes new ones in the same format.
- A tutor is listable only when `role='trainer'`, `tutor_status='approved'`, `is_verified=1`, `is_active=1`, not a university tutor, and covered by an **active** subscription within its period.
- DATETIME values are written as `YYYY-MM-DD HH:mm:ss`; users are soft-deleted via `deleted_at`.
- `structured_subjects`, `availability`, `verification_documents` are JSON-in-TEXT.

## Response envelope
```json
{ "success": true, "data": {}, "meta": { "page": 1, "limit": 20, "total": 0 } }
{ "success": false, "error": { "code": "UNAUTHORIZED", "message": "..." } }
```

## Next (per plan)
SMTP wiring for contact/OTP emails → tutor document & bio-video uploads →
PayChangu checkout WebView (client) → richer admin actions.
