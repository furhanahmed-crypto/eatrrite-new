# Eat Rrite (Next.js)

Next.js 16 rewamp of the Eat Rrite marketing site and appointment booking flow. The PHP site in the parent folder stays untouched; all new work lives in this app.

## Stack

- Next.js App Router + React
- Tailwind CSS 4 + shadcn/ui (`src/shared/ui`)
- next-themes (light / dark)
- Razorpay checkout
- Google Apps Script → Google Sheets + Meet (same deployed script as PHP)

## Folders

| Path | Purpose |
|---|---|
| `src/app/` | Routes + API |
| `src/components/{home,about,…}/` | Feature UI |
| `src/constants/` | Page section content arrays |
| `src/config/` | Site + schedule + env helpers |
| `src/shared/` | Shell, modals, shadcn primitives |
| `src/lib/` | Apps Script, Razorpay, storage, slots |
| `public/images/` | Static images (mirrors PHP assets) |
| `storage/` | Local holds/bookings JSON (gitignored) |

## Scripts

```bash
npm run dev
npm run lint
npm run build
```

## Environment

Copy `.env.example` → `.env.local` and fill secrets (Razorpay, Apps Script URL/secret, sheet id/tabs, admin password, mail fields). Never commit `.env.local`.

## Booking flow

1. `/appointment` — form + slot Modal (schedule from `src/config/schedule.js`)
2. `POST /api/appointment/create-order` — Razorpay order + local hold
3. Client pays → `POST /api/appointment/verify-payment`
4. `/appointment/thank-you` polls `POST /api/appointment/finalize` → Apps Script `book` (Sheet row + Meet)

## Admin

- `/admin/login` — password from `ADMIN_DASHBOARD_PASSWORD`
- `/admin/appointments` — month + day views, booking detail Sheet, hide/show slots via Apps Script

## Where to edit

- Booking rules + clinic hours: `src/config/schedule.js` (meeting/prep/hold/days-ahead/windows)
- Home (and other) copy: `src/constants/<page>/…Content.js`
- Brand tokens: `src/app/globals.css`
