# TutorConnect Malawi — Mobile App Implementation Plan

**Stack:** React Native (Expo) client + new Node.js/Express REST API against the **existing** MySQL database (`tutorconnectmw`).
**Goal:** A production-ready iOS/Android app that replicates and improves the website, without breaking the live PHP (CodeIgniter 4) site that shares the same database.

> Domain note: the brief mentioned "property listings, inquiries, favorites." This is a **tutor marketplace**, so those map to **tutor listings, contact/enquiry, and saved (favorite) tutors**.

> **⚠️ Model correction (authoritative — overrides the booking/messaging/customer-account sections below).**
> The app mirrors the website exactly:
> - **Parents/students have NO accounts.** Browsing and finding tutors is fully public.
> - **No in-app booking and no in-app chat.** Users contact tutors by **WhatsApp / call / email** or a message form that emails the tutor (like `Home::sendMessage`).
> - **Reviews are public** (name + rating, no login).
> - **Favorites are stored on the device** (no server favorites list).
> - **Accounts/login are for tutors and admins only.**
> Sections below describing customer accounts, bookings, or in-app messaging were the original draft and are superseded by this note.

---

## 1. Current Website Analysis

TutorConnect Malawi (CodeIgniter 4, PHP, MySQL, served via WAMP). Two audiences: **tutors/trainers** who subscribe to be discoverable, and **clients/parents** who find and contact tutors. Plus university-support and admin operations.

### Actors & roles
- `customer` — parents/students (browse, contact, review, buy resources).
- `trainer` — tutors (paid subscription, profile, subjects, availability, videos, papers, notices).
- University tutors — same `users` row but `portal_type = university` (separate portal, joined via `university_college_tutors`).
- `admin` / `sub-admin` — full back-office.

### Feature inventory (from routes & controllers)
| Area | Website behavior |
|---|---|
| **Discovery** | `find-tutors` search with filters: name, district, curriculum, subject, level, teaching_mode, sort. Tutor profile pages (`tutor/{slug}`). |
| **Auth** | Two-step registration (customer + multi-step tutor), OTP email verification, login (username/email + password), forgot/reset password, document resubmission via token. |
| **Tutor portal** | Dashboard, complete-profile, edit profile (personal/professional/preferences), availability, pricing, subjects, credentials/document upload, bio video, analytics, inquiries, subscription & checkout, video/paper uploads, notices. |
| **Booking/Inquiry** | `bookings` table — parent submits contact/booking to a tutor (trial/package/one-off/inquiry; online/in-person; reference code). |
| **Messaging** | `messages` table — threaded user-to-user messages tied to bookings, typed (inquiry, confirmation, reschedule…). |
| **Reviews** | Public reviews per tutor (1–5, optional anonymous); aggregate `rating`/`review_count` on user. |
| **Subscriptions** | Plans (Free Trial, Basic, Standard, Premium) gate features: `max_subjects`, `show_whatsapp`, `search_ranking`, `allow_video_upload`, etc. PayChangu payments + manual proof. `usage_tracking` enforces limits. |
| **Resources** | Past papers (free + paid via PayChangu, `past_paper_purchases`), video solutions. |
| **Parent requests** | Parents post a tutor request; tutors apply (`parent_requests`, `parent_request_applications`). |
| **University support** | Registration, lecture requests, matching, admin management. |
| **Teach in Japan** | Application + paid access (PayChangu). |
| **Notices** | Notice board with admin approval; tutor announcements (the "Success Stories" feed in the mockup). |
| **Admin** | Users/tutors CRUD, approvals, suspensions, subscriptions, library, curriculum, exports (Excel/PDF), backups, settings, payment review. |

### Key workflows
1. **Tutor onboarding:** register → OTP verify → complete profile + upload docs → admin approval (`tutor_status: pending→approved`) → choose subscription → pay (PayChangu/proof) → becomes searchable.
2. **Client → tutor:** search → view profile → "Book Now"/inquiry (`bookings`) → messaging thread → review after.
3. **Subscription gating:** features and search ranking depend on active `tutor_subscriptions` + plan flags; `usage_tracking` counts views/clicks/messages.

### Payments
**PayChangu** (Malawi mobile money + card): initiate → redirect/return → callback verification. Currency **MWK**. Used for subscriptions, past papers, Japan access.

---

## 2. Database Structure Review & Mapping

Reuse the existing schema. **Tutor profile data is consolidated into `users`** (no separate live tutor table — `tutors` is legacy). Passwords are **argon2id** (`$argon2id$...`).

### Core tables the API uses
- **`users`** — identity + tutor profile. Notable columns: `role`, `is_verified`, `is_active`, `tutor_status` (pending/approved/suspended/inactive), `email_verified_at`, `otp_code`/`otp_expires_at`, `reset_token`/`reset_expires_at`, `district`, `location`, `teaching_mode`, `bio`, `bio_video`, `profile_picture`, `cover_photo`, `whatsapp_number`, `phone_visible`/`email_visible`, `subscription_plan`, `subscription_expires_at`, `rating`, `review_count`, `featured`, `verification_documents` (JSON), `availability` (JSON), `structured_subjects` (JSON: `{curriculum:{levels:{level:[subjects]}}}`), soft-delete `deleted_at`.
- **`bookings`** — `reference_code` (unique), `tutor_id`, `client_id`, parent/client fields, `subjects_needed`, `booking_date/time`, `duration`, `session_type`, `teaching_mode`, `amount`, `currency`, `status`, `payment_status`, `meeting_link`.
- **`messages`** — `sender_id`, `receiver_id`, `booking_id?`, `subject`, `message`, `message_type`, `is_read`, `parent_message_id` (threading), `sent_at`, `read_at`.
- **`reviews`** — `tutor_id`, `reviewer_name`, `rating`, `comment`, `is_anonymous`, `created_at`.
- **`subscription_plans`** / **`tutor_subscriptions`** — plan flags + per-tutor subscription period, `payment_status`, `payment_proof_file`, `billing_months`.
- **`past_papers`** / **`past_paper_purchases`**, **`tutor_videos`**, **`notices`**, **`parent_requests`**/`parent_request_applications`, **`curriculum_subjects`**, **`subject_categories`**, **`education_levels`**, **`usage_tracking`**, **`profile_views`**, **`site_settings`**.

### Mapping rules (critical for not breaking the website)
- **Timestamps:** write MySQL `DATETIME` as `'YYYY-MM-DD HH:mm:ss'` (matches CodeIgniter). Use a single TZ (Africa/Blantyre, UTC+2) consistently.
- **Soft deletes:** always filter `deleted_at IS NULL` for users; never hard-delete.
- **Password verify/hash:** use the Node **`argon2`** package (`argon2.verify(hash, plain)`; new hashes via `argon2.hash(plain, {type: argon2.argon2id})`). bcrypt is incompatible with existing hashes.
- **JSON columns:** `verification_documents`, `availability`, `structured_subjects` are JSON-in-TEXT — parse defensively, re-serialize in the same shape the PHP views expect.
- **Searchable tutor =** `role='trainer' AND tutor_status='approved' AND is_verified=1 AND is_active=1 AND deleted_at IS NULL` and **not** a university tutor.

### New tables (additive migrations only — safe for the website)
1. **`favorites`** — `id, user_id, tutor_id, created_at` (unique `user_id+tutor_id`). The mockup's heart icon; no website equivalent.
2. **`device_tokens`** — `id, user_id, expo_push_token, platform, created_at, updated_at` for push notifications.
3. **`refresh_tokens`** — `id, user_id, token_hash, expires_at, revoked_at, device_info` for mobile JWT refresh.
4. (Optional) **`notifications`** — in-app notification feed (`user_id, type, title, body, data JSON, read_at`).

> All new tables are additive and prefixed/owned by the API; the PHP site ignores them. No existing column is altered.

---

## 3. Node.js / Express REST API

### Project layout (`mobile-app/backend`)
```
backend/
  src/
    config/        (db pool, env, paychangu, mailer)
    middleware/    (auth, role, rateLimit, validate, error)
    modules/
      auth/        (controller, service, routes, validators)
      tutors/      (search, profile, availability)
      bookings/    favorites/  messages/  reviews/
      subscriptions/ resources/  notices/  parentRequests/
      uploads/     notifications/  admin/
    db/            (knex/mysql2 queries, repositories)
    utils/         (jwt, password(argon2), pagination, slug)
    app.js  server.js
  migrations/      (knex — additive only)
  tests/
  .env.example
```

### Technology choices
- **Express** + **mysql2/promise** (or **Knex** query builder against the existing schema — no destructive migrations).
- **Auth:** JWT access token (15 min) + refresh token (30 days, stored hashed in `refresh_tokens`).
- **Validation:** `zod` or `express-validator`.
- **Security:** `helmet`, `express-rate-limit`, `cors` (allow Expo origins), input sanitization, parameterized queries only.
- **File uploads:** `multer` → write into the **website's** `public/uploads/...` paths so the existing site and admin can see them, OR migrate to S3-compatible storage and proxy. Keep the same relative-path convention stored in DB.
- **Mail:** reuse SMTP creds (OTP, password reset) via `nodemailer`.
- **Docs:** OpenAPI/Swagger at `/api/docs`.
- **Logging:** `pino` + request IDs.

### REST endpoints (v1, prefix `/api/v1`)
**Auth**
```
POST /auth/register/customer
POST /auth/register/tutor        (multi-step or single payload)
POST /auth/verify-otp            POST /auth/resend-otp
POST /auth/login                 POST /auth/refresh   POST /auth/logout
POST /auth/forgot-password       POST /auth/reset-password
GET  /auth/me                    PATCH /auth/me
```
**Tutors / discovery**
```
GET  /tutors                     (filters: q,district,curriculum,subject,level,teaching_mode,sort,page,limit)
GET  /tutors/:idOrSlug
GET  /tutors/:id/reviews         POST /tutors/:id/reviews
GET  /meta/districts | /meta/curricula | /meta/levels | /meta/subjects?curriculum=&level=
```
**Favorites**
```
GET /favorites   POST /favorites/:tutorId   DELETE /favorites/:tutorId
```
**Bookings / inquiries**
```
POST /bookings                   GET /bookings (mine, role-aware)
GET  /bookings/:id               PATCH /bookings/:id/status
```
**Messaging**
```
GET /conversations               GET /conversations/:userId/messages
POST /messages                   PATCH /messages/:id/read
```
**Tutor self-service** (`role=trainer`)
```
GET/PATCH /me/profile  /me/availability  /me/pricing  /me/subjects
POST /me/documents  /me/bio-video
GET  /me/inquiries  /me/analytics
GET  /me/subscription   POST /me/subscription/checkout   POST /me/subscription/proof
```
**Subscriptions / payments (PayChangu)**
```
GET  /plans
POST /payments/paychangu/initiate
POST /payments/paychangu/callback   (server-to-server; no auth, verify signature)
GET  /payments/:ref/status
```
**Content**
```
GET /notices  GET /notices/:id            (the "Success Stories" feed)
GET /past-papers  GET /past-papers/:id  POST /past-papers/:id/purchase  GET /past-papers/:id/download
GET /videos  GET /videos/:id
GET /parent-requests  POST /parent-requests  POST /parent-requests/:id/apply
```
**Notifications**
```
POST /devices/register   DELETE /devices/:token
GET  /notifications      PATCH /notifications/:id/read
```
**Admin** (`role in [admin, sub-admin]`) — mirror the highest-value web admin actions: approve/reject/suspend tutors, review payments, manage notices/video queue, list users. (Full admin can remain web-only; expose a focused subset.)

### Response envelope
```json
{ "success": true, "data": {...}, "meta": { "page": 1, "total": 120 } }
{ "success": false, "error": { "code": "INVALID_CREDENTIALS", "message": "..." } }
```

---

## 4. Authentication & User Management

- **Login parity:** accept username **or** email + password; verify with `argon2.verify` against `password_hash`. Enforce `is_verified=1` and `is_active=1` (same checks as web).
- **JWT claims:** `sub` (user id), `role`, `portal_type`, `token_version`. Short-lived access token; refresh rotation with reuse detection via `refresh_tokens`.
- **OTP verification:** reuse `otp_code`/`otp_expires_at`; 6-digit, 10-min expiry; set `email_verified_at` + `is_verified=1` on success.
- **Password reset:** reuse `reset_token`/`reset_expires_at` flow; email a deep link (`tutorconnect://reset?token=`).
- **Tutor gating:** tutors that aren't `approved` can log in but get a limited "pending approval / complete profile / resubmit documents" state (mirror web logic, incl. resubmission token).
- **University users:** detect `portal_type` at login and route to the university experience.
- **Authorization middleware:** `requireAuth`, `requireRole('trainer')`, `requireApprovedTutor`, `requireSubscriptionFeature('show_whatsapp')` (reads plan flags + active subscription).
- **Account management:** profile edit, change password (re-hash argon2id), privacy toggles (`phone_visible`/`email_visible`), delete/deactivate (soft).

---

## 5. Mobile App Architecture (React Native + Expo)

### Stack
- **Expo (SDK latest)**, **expo-router** (file-based) or React Navigation.
- **State/server cache:** **TanStack Query** (caching, retries, pagination) + lightweight **Zustand** for auth/session/UI.
- **Forms:** React Hook Form + zod (shared schemas with backend where possible).
- **HTTP:** Axios instance with auth interceptor + silent refresh.
- **Secure storage:** `expo-secure-store` for tokens.
- **Notifications:** `expo-notifications` + `expo-device`.
- **Media:** `expo-image-picker`, `expo-image` (caching), `expo-av`/`expo-video` for bio/solution videos, `expo-file-system` for downloads (past papers).
- **UI:** design system (e.g. Tamagui/NativeWind or RN Paper) matching the purple/blue mockup; reusable `TutorCard`, `RatingStars`, `BadgeChip`, `FilterSheet`.
- **i18n & money:** format MWK; Africa/Blantyre time.

### Folder layout (`mobile-app/frontend`)
```
app/                       (expo-router routes)
  (auth)/                  login, register, verify-otp, forgot
  (tabs)/                  index(home), search, favorites, messages, profile
  tutor/[id].tsx           booking/[id].tsx   conversation/[id].tsx
src/
  api/        (typed clients per module)
  components/  hooks/  store/  theme/  utils/  types/
```
- **Offline/perf:** cache tutor lists & metadata, optimistic favorites, image placeholders, FlatList virtualization, debounce search.
- **Env config:** `app.config.ts` with `API_URL` per environment; EAS Build profiles (dev/preview/prod).

---

## 6. Screen-by-Screen Breakdown & Navigation

**Auth stack:** Splash → Onboarding → Login → Register (role choice → customer or tutor multi-step) → OTP Verify → Forgot/Reset.

**Main (bottom tabs)** — matches mockup:
1. **Home** — greeting + search bar + filter icon; "Top Rated Tutors 🔥" horizontal carousel of `TutorCard` (photo, name, stars, review count, badges, experience, school, languages, price, **Book Now**, favorite heart); "Tutor's Success Stories" (notices feed); "Who is Near to You?" (district/location-based).
2. **Search/Explore** — full filters (district, curriculum, level, subject, teaching mode, sort), results list, empty/loading states.
3. **Favorites** — saved tutors.
4. **Messages** — conversation list → thread (bubbles, typed messages, read receipts).
5. **Profile/Account** — for clients: account + bookings + reviews given; for tutors: dashboard entry.

**Detail/flows:**
- **Tutor Profile** — cover/photo, bio, bio video, subjects by curriculum/level, availability, rating + reviews, contact (WhatsApp if plan allows), **Book Now**.
- **Booking/Inquiry** — form (level, subjects, date/time, mode, notes) → confirmation w/ reference code → message thread.
- **Tutor Dashboard** (trainer role) — profile completeness, inquiries, analytics (views/clicks), subscription status, upload docs/video/papers, post notice.
- **Subscription** — plan comparison, PayChangu checkout / upload proof, status.
- **Resources** — past papers (purchase/download), video solutions.
- **Parent Requests** — browse/post requests; tutors apply.
- **Notifications** — feed.

**Stories success feed** = `notices` (admin-approved) rendered as social cards (the right-hand mockup).

---

## 7. Listings, Search, Filters, Favorites, Inquiries

- **Listing query** = the "searchable tutor" predicate (§2) with **subscription-aware ordering**: `search_ranking` (top→priority→normal→low), then `featured`, then `rating`, then `review_count`. Paginate (`page`,`limit`).
- **Filters:** `district`, `teaching_mode` direct columns; `curriculum`/`level`/`subject` parsed from `structured_subjects` JSON. Provide `/meta/*` endpoints (mirroring `Api::getCurriculumLevels/Subjects`) so filter dropdowns load from `curriculum_subjects`.
- **Search by name:** match `first_name`/`last_name`/`username`.
- **Favorites:** new `favorites` table; optimistic UI; `GET /favorites` joins tutor cards.
- **Inquiries/bookings:** `POST /bookings` generates a unique `reference_code` (e.g. `TC-{YYYYMMDD}-{rand}`), respects `session_type`/`teaching_mode` enums, and **records `usage_tracking`** (clicks/messages) so subscription limits stay consistent with the website. Auto-create an opening `messages` row of type `inquiry`.

---

## 8. User Dashboards & Account Management

- **Client dashboard:** my bookings (status, reference, tutor), my messages, favorites, reviews submitted, profile/privacy settings.
- **Tutor dashboard:** profile completeness meter, `tutor_status` banner (pending/approved/suspended + resubmission), inquiries inbox, analytics from `profile_views`/`usage_tracking`, subscription card (plan, expiry, upgrade), content management (subjects, availability, pricing, documents, bio video, papers, videos, notices).
- **University tutor:** lecture requests + profile (read from `university_college_tutors`).
- **Account:** edit identity, change password, privacy toggles, manage devices/notifications, deactivate.

---

## 9. Notifications & Messaging

- **Messaging:** REST endpoints over the `messages` table (threaded via `parent_message_id`/`booking_id`). Poll with TanStack Query on the conversation screen; later upgrade to **WebSocket (Socket.IO)** for real-time. Mark-read updates `is_read`/`read_at`.
- **Push (Expo):** register `expo_push_token` → `device_tokens`. Trigger on: new inquiry/booking, new message, booking status change, subscription expiry reminder (reuse `subscription_renewal_reminders` logic), tutor approval/rejection, new approved notice. Server sends via Expo Push API; also persist to `notifications` for the in-app feed.
- **Email parity:** keep transactional emails (OTP, reset, approval) via nodemailer so web + mobile behave identically.

---

## 10. Admin Management (mobile subset)

Keep the full back-office on the web; expose a **focused admin/sub-admin** API + light screens for on-the-go ops:
- Tutor approval queue: view docs → approve/reject/suspend/request-resubmission (`tutor_status`, `approved_at`).
- Payment review: verify/reject `tutor_subscriptions` proofs and past-paper payments.
- Notice & video approval queues.
- User/tutor list + quick actions (toggle status).
- Dashboard counters (pending tutors, payments, notices).

All gated by `requireRole(['admin','sub-admin'])`, mirroring the `auth:sub-admin` web filter.

---

## 11. Performance, Scalability & Security

**Performance**
- MySQL connection pool; add covering indexes if needed for the tutor search predicate (existing indexes on `role`, `tutor_status`, `is_verified`, `district` already help).
- Cache `/meta/*` and plan lists (in-memory/Redis, short TTL).
- Server-side pagination everywhere; thumbnail/responsive images; CDN/static serving for `/uploads`.
- Client: TanStack Query caching, list virtualization, image caching, debounced search.

**Scalability**
- Stateless API (JWT) → horizontal scaling behind a load balancer.
- Offload media to S3-compatible storage + CDN as growth demands (keep DB paths backward-compatible).
- Background jobs (queue) for emails, push, renewal reminders.

**Security**
- argon2id verify/hash; never log secrets; rotate JWT signing keys; refresh-token reuse detection.
- `helmet`, strict CORS, global + per-route rate limiting (login, OTP, reset).
- Parameterized queries (no string SQL); validate/normalize all input with zod.
- Enforce ownership/role on every mutating route; subscription-feature checks server-side (never trust client).
- File-upload validation (mime/size), store outside web root or sanitize; signed URLs for paid downloads.
- **Remove dev-only website routes in prod** (`database-inspector`, `show-all-data`, `test-registration`) — they expose data and must not be reachable.
- PayChangu callback signature verification; idempotent payment handling.
- TLS everywhere; HSTS on API.

---

## 12. Mobile UX Improvements Over the Website

1. **One-tap discovery** with persistent filters, recent searches, and "near me" using device location → nearest districts.
2. **Native booking** with date/time pickers, instant reference code, and push confirmations (vs web forms).
3. **Real-time messaging** with read receipts and push (web is request/refresh).
4. **Favorites & follow** tutors (new capability).
5. **Richer profiles:** inline bio-video playback, swipeable galleries, subject chips grouped by curriculum.
6. **Tutor productivity:** mobile dashboard for inquiries/analytics, photo capture for documents, in-app subscription upgrade.
7. **Offline-friendly** cached lists and optimistic favorites.
8. **Accessibility & localization:** large tap targets, MWK formatting, English + (optionally) Chichewa.
9. **Trust signals:** verification badges, plan badges, review summaries front-and-center.
10. **Deep links & sharing:** `tutorconnect://tutor/:id` for shareable profiles and email/reset links.

---

## Delivery Roadmap (phased)

**Phase 0 — Foundations (wk 1):** Backend scaffold, DB pool, env, argon2 login parity, JWT/refresh, additive migrations (`favorites`, `device_tokens`, `refresh_tokens`), Swagger, CI.

**Phase 1 — Auth + Discovery (wk 2–3):** register/OTP/login/reset; tutor search + filters + `/meta`; tutor profile; reviews read. Expo app shell, auth flow, Home + Search + Tutor Profile.

**Phase 2 — Engagement (wk 4–5):** favorites, bookings/inquiries, messaging, notices feed, push notifications, notifications feed.

**Phase 3 — Tutor portal (wk 6–7):** profile/availability/pricing/subjects edit, document & video upload, analytics, subscriptions + PayChangu checkout/proof.

**Phase 4 — Resources & extras (wk 8):** past papers (purchase/download), video solutions, parent requests, university subset.

**Phase 5 — Admin subset + hardening (wk 9):** approval/payment/notice queues; security review, load test, EAS builds, store submission.

**Cross-cutting:** automated tests (API integration + RN component), staging env pointed at a DB **copy** before touching live data, and a data-safety review to guarantee the API never breaks the running website.