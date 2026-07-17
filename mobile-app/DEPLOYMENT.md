# TutorConnect — Deploying the Backend to Render & Building the App

This guide takes the mobile-app backend from your local WAMP machine to a public
Render URL, then builds an installable Android app that talks to it.

Your server code is already deploy-ready (it binds `0.0.0.0:$PORT` and exposes
`GET /api/v1/health`). The work is mostly **relocating two things that currently
live on your PC** — the database and file uploads — and wiring up config.

---

## The big picture (read this first)

The app backend and the PHP website **share one MySQL database** (`tutorconnectmw`).
Right now that database runs on your PC (`127.0.0.1:3306` via WAMP), and uploads
are written into the website's `public/uploads/` folder. **Render cannot reach
your PC**, and Render's disk is wiped on every deploy. So before deploying you
must decide where two things live:

| Concern | Today (local) | Must become |
|---|---|---|
| **MySQL** | WAMP on your PC | A MySQL server reachable from the internet |
| **Uploads** (photos, docs) | Website's `public/uploads/` folder | Persistent storage the backend can write + serve |

Everything else (JWT, PayChangu, SMTP) is just environment variables you copy over.

---

## STEP 0 — Decide where MySQL will live

Render only offers managed **PostgreSQL**, not MySQL, and your code is MySQL-specific
— so you need MySQL hosted elsewhere. Pick one:

- **Option A — Your website's cPanel host (recommended if the website is/will be on cPanel).**
  Your SMTP already points at `c5.my-control-panel.com`, so you likely have a cPanel
  account. In cPanel → **Remote MySQL**, allow the backend to connect (see Step 1),
  and point BOTH the website and the Render backend at that same database. This keeps
  the shared-database model intact.

- **Option B — A managed MySQL (Railway, Aiven, or Clever Cloud have free tiers).**
  Create a MySQL instance, import your `tutorconnectmw` schema + data into it, then
  point BOTH the website and the backend at it. Cleaner isolation, but you migrate the
  data and repoint the website too.

> ⚠️ Whichever you choose, the **website and the app must use the same database**, or
> they'll show different tutors/subscriptions. Don't put the app on one DB and leave
> the website on another.

---

## STEP 0b — Decide where uploads will live

The backend writes uploaded photos/PDFs to a folder and serves them at `/uploads`.
On Render this needs rethinking. Pick one:

- **Option A — Render Persistent Disk (simplest, needs a paid instance).**
  Attach a disk (e.g. mounted at `/var/data/uploads`), set `UPLOADS_DIR=/var/data/uploads`.
  New uploads persist across deploys. **Caveat:** persistent disks require the **Starter
  plan ($7/mo)** — the free plan's disk is ephemeral (wiped on restart), so uploads
  would vanish. Also, your 3 existing tutor file-photos live in the website's folder;
  they'll 404 until re-uploaded via the app or copied onto the disk.

- **Option B — Object storage: Cloudflare R2 or AWS S3 (best long-term).**
  Uploads go to an S3-compatible bucket and are served from there. Survives redeploys,
  cheap, and both website and app can read the same bucket. Requires a code change to
  the upload handlers (multer → S3). **I can implement this for you — just ask.**

- **Option C — Keep uploads on the website, backend uploads via SFTP/API.**
  Most faithful to the current shared-folder model, but the most work. Not recommended
  for a first deploy.

For a fast first launch: **Option A** on a Starter instance. For a real product:
**Option B (R2)**.

> Note: your 10 demo tutors use external `randomuser.me` photo URLs, so they render
> regardless. Only the 3 real tutors with uploaded photos are affected by this choice.

---

## STEP 1 — Get your MySQL reachable & note the credentials

You need these five values for the backend:

```
DB_HOST      e.g. c5.my-control-panel.com   (NOT localhost/127.0.0.1)
DB_PORT      3306
DB_NAME      tutorconnectmw
DB_USER      your db user
DB_PASSWORD  your db password
```

- **cPanel:** cPanel → *Remote MySQL* → add the host that's allowed to connect. Render
  free services use **dynamic outbound IPs**, so you may need to allow `%` (any host)
  with a strong password, or upgrade to a Render paid plan for **static outbound IPs**
  you can whitelist precisely. Prefer whitelisting if you can.
- **Managed MySQL:** the provider gives you host/port/user/password directly, plus an
  option to allow public connections.

Test from your machine before deploying:
```bash
mysql -h <DB_HOST> -P 3306 -u <DB_USER> -p tutorconnectmw -e "SELECT COUNT(*) FROM users;"
```

---

## STEP 2 — Push the code to GitHub

Render deploys from a Git repo. Your `.env` is already git-ignored (good — secrets
never get committed).

```bash
# from the repo root: c:\wamp64\www\tutorconnect_live
git add mobile-app/backend mobile-app/frontend/eas.json mobile-app/DEPLOYMENT.md
git commit -m "Prepare mobile backend for Render deployment"
# push to a GitHub repo you control
git push origin main
```

The backend lives in a **subdirectory** (`mobile-app/backend`) — that's fine, Render
lets you set a Root Directory.

---

## STEP 3 — Create the Render Web Service

1. Go to **render.com → New → Web Service**, connect your GitHub repo.
2. Configure:
   - **Root Directory:** `mobile-app/backend`
   - **Runtime:** Node
   - **Build Command:** `npm ci`
   - **Start Command:** `npm start`
   - **Health Check Path:** `/api/v1/health`
   - **Region:** Frankfurt (closest Render region to Malawi)
   - **Instance type:** Free to try it; **Starter ($7/mo)** for no cold-start
     spin-down and to enable a persistent disk.
3. Create the service (it'll fail the first deploy until env vars are set — that's fine).

> **Free plan caveat:** free web services **sleep after ~15 min of inactivity** and
> take ~30–50s to wake. Your app now retries connection failures, so it recovers, but
> the first request after idle is slow. Starter removes this.

---

## STEP 4 — Set the Environment Variables on Render

In the service's **Environment** tab, add these. Copy the secret values from your
local `mobile-app/backend/.env`. **Do not set `PORT`** — Render injects it and the
app reads it automatically.

```
NODE_ENV=production
API_PREFIX=/api/v1

# Database (from Step 1)
DB_HOST=<your public mysql host>
DB_PORT=3306
DB_NAME=tutorconnectmw
DB_USER=<user>
DB_PASSWORD=<password>
DB_TIMEZONE=+02:00

# Auth — reuse the SAME secrets as local so existing sessions/tokens stay valid
JWT_ACCESS_SECRET=<copy from local .env>
JWT_REFRESH_SECRET=<copy from local .env>
JWT_ACCESS_TTL=15m
JWT_REFRESH_TTL=365d

# Public base URL — set AFTER you know your Render URL (see Step 5)
PUBLIC_BASE_URL=https://<your-service>.onrender.com

# Uploads (Step 0b). For a Render persistent disk:
UPLOADS_DIR=/var/data/uploads

# PayChangu (TEST keys for now; swap to live keys when ready)
PAYCHANGU_PUBLIC_KEY=<copy from local .env>
PAYCHANGU_SECRET_KEY=<copy from local .env>
PAYCHANGU_CALLBACK_URL=https://<your-service>.onrender.com/api/v1/payments/paychangu/callback
PAYCHANGU_RETURN_URL=https://<your-service>.onrender.com/api/v1/payments/return

# Mail (reuse the website's SMTP)
SMTP_HOST=c5.my-control-panel.com
SMTP_PORT=587
SMTP_USER=info@tutorconnectmw.com
SMTP_PASSWORD=<copy from local .env>
MAIL_FROM=TutorConnect Malawi <info@tutorconnectmw.com>

# App version gate (optional but recommended)
MIN_APP_VERSION=0.1.0
LATEST_APP_VERSION=0.1.0
ANDROID_STORE_URL=
IOS_STORE_URL=

# CORS — native apps don't send an Origin, so this is only for any web client.
CORS_ORIGINS=
```

If you chose the Render persistent disk for uploads: **Disks** tab → add a disk,
mount path `/var/data/uploads`, small size (1 GB is plenty to start).

---

## STEP 5 — Deploy & verify the backend

1. Trigger a deploy (Render auto-deploys on push, or click **Manual Deploy**).
2. Watch the logs for:
   ```
   [db] connected to tutorconnectmw@<your host>:3306
   [api] listening on http://localhost:10000/api/v1
   ```
   If the DB line errors, your MySQL isn't reachable — revisit Step 1.
3. Copy your service URL (e.g. `https://tutorconnect-api.onrender.com`) and set
   `PUBLIC_BASE_URL` + the two PayChangu URLs to use it (Step 4), then redeploy.
4. Smoke-test from anywhere:
   ```bash
   curl https://<your-service>.onrender.com/api/v1/health
   # -> {"success":true,"data":{"status":"ok"}}

   curl "https://<your-service>.onrender.com/api/v1/tutors?limit=3"
   # -> a JSON list of tutors
   ```

If those work, your backend is live.

---

## STEP 6 — Point the app at Render and build it

You'll build with **EAS Build** (Expo's cloud builder) — no Android Studio needed.

### 6a. Install tooling & log in
```bash
cd mobile-app/frontend
npm install -g eas-cli
eas login                 # create a free Expo account if needed
eas init                  # links this app to your Expo account, writes the projectId
```

### 6b. Set the production API URL
Edit `mobile-app/frontend/eas.json` — replace `REPLACE-WITH-YOUR-SERVICE` in BOTH the
`preview` and `production` profiles with your real Render URL:
```
"EXPO_PUBLIC_API_URL": "https://tutorconnect-api.onrender.com/api/v1"
```
Also update the fallback in `app.json` → `expo.extra.apiUrl` to the same URL (belt and
braces — this is what a production build uses when there's no dev server).

> Your local `.env` still has the LAN IP for day-to-day development — leave it. EAS
> uses the value from `eas.json`, so builds always point at Render.

### 6c. Build an installable APK (for testing on real phones)
```bash
eas build --profile preview --platform android
```
EAS builds in the cloud (~10–20 min) and gives you a **downloadable .apk URL**. Send
it to any Android phone, install it (allow "install from unknown sources"), and it
talks to your Render backend over the internet — no Wi-Fi/LAN needed.

### 6d. Build for the Play Store (when ready to publish)
```bash
eas build --profile production --platform android   # produces an .aab
eas submit  --profile production --platform android  # uploads to Play Console
```
(You'll need a one-time Google Play Developer account, $25.)

---

## STEP 7 — After it's live

- **Bump the version** for each release: `app.json` → `expo.version` (e.g. `0.1.1`).
  The `production` profile auto-increments the Android versionCode.
- **Version gate:** when you ship a build that needs a newer API, set
  `MIN_APP_VERSION` on Render to force old apps to update (the app already reads
  `/app-config`).
- **PayChangu go-live:** swap the TEST keys for LIVE keys in Render env vars. The
  code auto-detects live vs test from the key prefix — no code change needed.
- **Keep-warm (free plan):** free services sleep. A simple cron (e.g. cron-job.org)
  hitting `/api/v1/health` every 10 min keeps it awake, or upgrade to Starter.

---

## Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Deploy logs show DB connection error | MySQL not internet-reachable / IP not whitelisted | Step 1 — Remote MySQL host allowlist |
| App shows "Can't reach the server" | Wrong `EXPO_PUBLIC_API_URL` baked into the build | Fix `eas.json`, rebuild |
| Tutor photos 404 in the built app | Uploads not on Render (Step 0b) | Use a persistent disk or R2; re-upload the 3 real photos |
| First request very slow, then fine | Free-plan cold start | Starter plan, or keep-warm cron |
| PayChangu checkout fails in prod | Callback/return URLs still point at localhost/LAN | Set `PAYCHANGU_*_URL` to the Render URL |
| OTP/contact emails not arriving | SMTP creds/port wrong on Render | Re-check `SMTP_*` env vars |

---

## What I can do for you next

- **Wire uploads to Cloudflare R2/S3** (Option B) so images survive redeploys and both
  website and app share them — this is the most robust choice.
- **Add a `render.yaml` blueprint** so the service + env vars are defined as code.
- **Migrate the database** to a managed MySQL and repoint the website.

Just say which and I'll implement it.
