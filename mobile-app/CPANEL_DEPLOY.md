# Deploying the Backend to cPanel — simbiadmin.blueairengineering.com

You're hosting the Node backend on your own cPanel domain. cPanel runs Node apps
with **Phusion Passenger** via the **"Setup Node.js App"** tool (CloudLinux Node.js
Selector). This is simpler than Render: the filesystem is persistent (uploads just
work), and if MySQL is on the same account the app reaches it at `localhost`.

The app URL will be: **`https://simbiadmin.blueairengineering.com/api/v1`**

---

## What to upload

Upload the **contents of `mobile-app/backend/`** — but NOT `node_modules` (cPanel
installs those for you). So the app folder on the host should contain:

```
src/                     (the whole folder)
package.json
package-lock.json
.env                     (you create this on the host — see Step 3)
```

**A ready-made archive is already built for you:** `mobile-app/tutorconnect-api.tar.gz`
(≈65 KB — excludes `node_modules` and secrets, uses Linux-safe forward-slash paths).

Upload it via cPanel **File Manager** into your chosen app folder (e.g.
`/home/<cpaneluser>/tutorconnect-api`), then **Extract** it there. cPanel's File
Manager extracts `.tar.gz` natively.

> Rebuild the archive any time you change backend code:
> ```bash
> cd mobile-app/backend
> tar -czf ../tutorconnect-api.tar.gz --exclude=node_modules --exclude=.env --exclude=.expo --exclude=.git .
> ```
> (Avoid Windows' "Send to → Compressed folder" / PowerShell `Compress-Archive` —
> those write backslash paths that Linux extractors mangle into files literally
> named `src\server.js`.)

---

## Step 1 — Create the Node.js app in cPanel

cPanel → **Setup Node.js App** → **Create Application**:

- **Node.js version:** 20.x (the code requires Node ≥ 20)
- **Application mode:** Production
- **Application root:** the folder you extracted into, e.g. `tutorconnect-api`
  (relative to your home dir)
- **Application URL:** `simbiadmin.blueairengineering.com` (map the whole
  subdomain to the app — no subpath)
- **Application startup file:** `src/server.js`

Click **Create**.

> If your cPanel insists the startup file must be at the app root (some older
> versions do), create a file `app.js` in the app root containing just:
> `import './src/server.js';` — and set the startup file to `app.js`.

---

## Step 2 — Install dependencies

On the same Setup Node.js App page for your app, click **Run NPM Install**. It reads
`package.json` + `package-lock.json` and installs into a virtualenv cPanel manages.

(If you prefer the terminal: cPanel gives you a command to `source` the app's
virtualenv, then run `npm ci`.)

---

## Step 3 — Create the `.env` file

In the app root (next to `package.json`), create a file named `.env`. Use
`.env.production.example` (included in this repo) as the template. Fill in:

- **DB_USER / DB_PASSWORD** — the credentials you'll share once MySQL is set up.
  Keep `DB_HOST=localhost` if MySQL is on this same cPanel account.
- **JWT_ACCESS_SECRET / JWT_REFRESH_SECRET** — copy verbatim from your local
  `backend/.env` so existing app logins keep working.
- **PAYCHANGU_PUBLIC_KEY / PAYCHANGU_SECRET_KEY** — copy from local (test keys).
- **SMTP_PASSWORD** — copy from local.

Everything else (URLs for this domain, uploads path) is already filled in the
template. Alternatively, set these as **Environment Variables** in the Setup
Node.js App UI instead of a file — either works.

> The database is the last piece. Once you've created the MySQL DB + user in cPanel
> (MySQL Databases → create DB, create user, add user to DB with ALL privileges),
> put those into `DB_USER`/`DB_PASSWORD` and import your `tutorconnectmw` schema +
> data (see "Database import" below).

---

## Step 4 — Import the database

The app and website share `tutorconnectmw`. On the host:

1. cPanel → **MySQL Databases** → create a database (its real name will be prefixed,
   e.g. `cpaneluser_tutorconnectmw`) and a user, then add the user to the DB with
   **All Privileges**.
2. Export your local DB and import it:
   ```bash
   # locally, from WAMP's mysql/bin (or add it to PATH):
   mysqldump -u root tutorconnectmw > tutorconnectmw.sql
   ```
   Then cPanel → **phpMyAdmin** → select the new database → **Import** →
   upload `tutorconnectmw.sql`. (For large files, use cPanel's Backup or a gzip.)
3. Set `DB_NAME` in `.env` to the **prefixed** name (e.g. `cpaneluser_tutorconnectmw`).

> If the website will also run on this host, point it at the same database so both
> stay in sync. If the website stays elsewhere, decide which host owns the DB and
> point both at it (remote MySQL for the other side).

---

## Step 5 — Start & verify

Back on Setup Node.js App, click **Restart** (or Start). Then test from anywhere:

```bash
curl https://simbiadmin.blueairengineering.com/api/v1/health
# -> {"success":true,"data":{"status":"ok"}}

curl "https://simbiadmin.blueairengineering.com/api/v1/tutors?limit=3"
# -> a JSON list of tutors
```

If health works but `/tutors` errors, the DB isn't connected — check the app's
**stderr log** (Setup Node.js App shows the log path) for the `[db] connection
failed` line, and re-check `DB_*` in `.env`.

**HTTPS:** make sure the subdomain has an SSL cert (cPanel → SSL/TLS Status →
AutoSSL). The app must be reachable over `https://` because the mobile app calls
https and Android blocks plain http by default.

---

## Step 6 — Uploads folder

Uploads are written to `./uploads` in the app root and served at
`https://simbiadmin.blueairengineering.com/api/v1/../uploads/...` (i.e.
`PUBLIC_BASE_URL/uploads/...`). The folder is created automatically on first upload.

Your **3 existing real-tutor photos** live in the old website's `public/uploads`.
Copy those files into the new `uploads/` folder (preserving the subfolder path like
`uploads/profile_photos/...`) if you want those photos to show, or have those tutors
re-upload from the app. (The 10 demo tutors use external image URLs and are fine.)

---

## Step 7 — Build the Android app

The app is already pointed at your domain (`eas.json` + `app.json`). Build it:

```bash
cd mobile-app/frontend
npm install -g eas-cli
eas login
eas init                                   # links to your Expo account
eas build --profile preview --platform android
```

You'll get a downloadable **.apk** URL — install it on any Android phone and it talks
to your cPanel backend over the internet. For the Play Store, use
`--profile production` (produces an `.aab`) then `eas submit`.

---

## Redeploying later (when you change backend code)

1. Re-upload the changed files under `src/` (File Manager, or set up cPanel Git).
2. Setup Node.js App → **Restart**.

Passenger picks up changes on restart. There's no build step — it's plain Node.

---

## cPanel gotchas checklist

- [ ] Node version set to **20+** (ESM `import` syntax requires it).
- [ ] Startup file = `src/server.js` (or a root `app.js` that imports it).
- [ ] `.env` present in the app root (not in `src/`), with real DB creds.
- [ ] AutoSSL issued for the subdomain (app must serve **https**).
- [ ] `DB_NAME` uses the cPanel-**prefixed** database name.
- [ ] Ran **NPM Install** after uploading.
- [ ] `uploads/` folder writable (cPanel apps can write in their app root by default).
- [ ] After any code change or `.env` edit → **Restart** the app.
