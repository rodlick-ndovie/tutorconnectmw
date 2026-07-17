# TutorConnect Mobile (Expo)

React Native (Expo Router + TypeScript) client for the TutorConnect Malawi API.

See [`../IMPLEMENTATION_PLAN.md`](../IMPLEMENTATION_PLAN.md) for the full roadmap and
[`../backend/README.md`](../backend/README.md) for the API it talks to.

## Setup
```bash
cd mobile-app/frontend
npm install
npx expo start
```
**No IP configuration needed.** In development the app derives the API host from the
Metro dev-server URI (e.g. `192.168.1.194:8081` → `http://192.168.1.194:4000/api/v1`),
so a phone on the same Wi-Fi reaches the API automatically even when your IP changes.
The backend likewise auto-detects its own LAN address for image URLs.

Resolution order for the API base URL ([src/api/client.ts](src/api/client.ts)):
1. `EXPO_PUBLIC_API_URL` (explicit override)
2. Metro dev-server host + port 4000 (dev — the automatic path)
3. `app.json` → `expo.extra.apiUrl` (standalone/production builds, where there is no dev server)
4. `http://localhost:4000/api/v1`

> For a **production build**, set `expo.extra.apiUrl` (or `EXPO_PUBLIC_API_URL`) to your
> public API origin — there is no dev server to infer it from.

## Model (matches the website)
- **Public-first: no login to browse and find tutors.** Parents/students have no accounts.
- Contact a tutor by **WhatsApp / call / email**, or a message form that emails them.
- **Reviews are public** (name + rating, no account).
- **Favorites are stored on the device** (AsyncStorage) — there is no server favorites list.
- The **Account** tab is for **tutors/admins** to log in and manage their profile.

## Architecture
- **expo-router** file-based navigation (`app/`).
- **TanStack Query** for server state; **Zustand** for the (optional) tutor/admin session and local favorites.
- **Axios** client ([src/api/client.ts](src/api/client.ts)) with bearer auth + silent refresh.
- Tokens in **expo-secure-store**; favorites in **AsyncStorage** ([src/store/favorites.ts](src/store/favorites.ts)).

## Screens
| Route | Purpose |
|---|---|
| `(tabs)/index` | Home — top-rated carousel + Success Stories feed + search entry |
| `(tabs)/search` | search + district/curriculum/sort filters |
| `(tabs)/favorites` | saved tutors (on-device) |
| `(tabs)/profile` | Account — tutor/admin login, or profile + logout |
| `tutor/[id]` | profile, subjects, reviews + **Contact Tutor** (WhatsApp/call/email/message) and **Write a review** |

## Implemented
- **Public:** tutor discovery (home/search/detail), on-device favorites, public reviews,
  public contact (WhatsApp/call/email/message form), notices feed, resources (past
  papers + video solutions).
- **Tutor/admin:** login + session restore, account screen, **tutor dashboard** (edit
  profile, availability, subjects, analytics, subscription), **PayChangu checkout WebView**
  with server-side verification, **push registration** (`expo-notifications` → `/devices`),
  **in-app notifications feed**, **admin queues** (overview, tutor approvals, payment review).

## Possible polish (later)
Cover-photo / document uploads from the device, richer admin actions (notice/video
queues have endpoints but no screens yet), real-time chat if the model ever changes,
and EAS build + store submission.


cd mobile-app/backend  && npm run dev      # API → http://localhost:4000/api/v1 (dev DB)
cd mobile-app/frontend && npx expo start    # set expo.extra.apiUrl to your LAN IP for a device
