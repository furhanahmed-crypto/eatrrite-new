# Eat Rrite Website

Marketing site and online consultation booking for **Eat Rrite** — holistic nutrition coaching (Hyderabad & Dehradun, online consultations).

---

## Current tech stack

| Layer | Technology | Used for |
| --- | --- | --- |
| Public website | PHP includes + HTML sections + custom CSS/JS | Pages: home, about, programs, pricing, contact, appointment |
| Styling / UX | Custom CSS (`assets/css/style.css`), Font Awesome, GSAP (CDN) | Layout, animations, responsive UI |
| Appointment booking | PHP APIs under `appointment-form/` + browser JS | Slot picker, Razorpay payment, thank-you / Meet polling |
| Payments | Razorpay (server-side orders + signature verify) | Consultation confirmation fee |
| Booking storage (live) | **Google Sheets via Google Apps Script** | Appointments list, booked slots, disabled slots |
| Local booking state | `appointment-form/storage/bookings.json` + `holds.json` | Payment finalize state + temporary slot holds during checkout |
| Weekly schedule | `appointment-form/config.php` → `slot_schedule` | Hours / Friday off (no Sheets) |
| Google Meet | Apps Script → Google Calendar API (`hangoutsMeet`) | Meet link created when booking is finalized |
| Email | PHPMailer (`email/`) + SMTP secrets | Customer + admin booking confirmation emails |
| Admin calendar | PHP under `admin-dashboard/` | Month/day calendar, hide/show slots, view bookings |
| Secrets | `includes/secrets.php` (gitignored) | Razorpay keys, Apps Script URL/secret, SMTP, admin password |

There is **no application database** today. Sheets + local JSON are the system of record for bookings.

---

## Important URLs (local)

With `php -S localhost:8080` from the project root:

| Page | URL |
| --- | --- |
| Home | http://localhost:8080/ |
| Book appointment | http://localhost:8080/appointment.php |
| Admin calendar | http://localhost:8080/admin-dashboard/appointments-calendar/ |
| Admin login | http://localhost:8080/admin-dashboard/login.php |

Admin password is `admin_dashboard_password` in `includes/secrets.php`.

---

## Folder map

```
eatrrite-new/
├── index.php, about.php, programs.php, pricing.php, contact.php, appointment.php
├── includes/                 # site config, secrets, header/footer
├── sections/                 # marketing page sections
├── assets/                   # public CSS/JS/images
├── appointment-form/
│   ├── config.php            # secrets + booking settings + weekly schedule
│   ├── bootstrap.php
│   ├── api/                  # slots, create-order, verify, finalize (public URLs)
│   ├── assets/               # appointment CSS/JS (public URLs)
│   ├── views/                # form, calendar modal, success panel
│   ├── src/                  # PHP domain services
│   ├── storage/              # bookings.json + holds.json only (gitignored)
│   └── scripts/google-apps-script/
├── admin-dashboard/
│   ├── bootstrap.php, auth.php, login.php, logout.php
│   ├── layout-start.php, layout-end.php
│   ├── src/                  # AppointmentFeed, CalendarPresenter
│   └── appointments-calendar/  # public admin UI URL + assets/components
└── email/                    # PHPMailer + templates
```

---

## Why booking & admin feel slow (current architecture)

Slowness is **not** mainly from PHP page rendering or CSS. Booking/admin still talk to Google for **booked slots / disabled slots / Meet**, but **weekly hours and Friday-off now live in PHP config** (`appointment-form/config.php` → `slot_schedule`) so the appointment page and admin schedule text no longer wait on Sheets to render.

### 1. Book Appointment (opening the slot picker)

Page HTML (fee note / hours text) uses **local `slot_schedule` in config.php** — no Google.

When the user opens the calendar modal, the browser calls `appointment-form/api/slots.php` → `AppointmentService::availability()`. That path still:

1. Loads **disabled slots** via Apps Script (cached ~30 s) when building availability.
2. Loads **booked appointments** from Apps Script (`list` → Sheet).
3. Builds available times in PHP from the local weekly schedule.

Each Apps Script call is an HTTPS round trip that:

- Cold-starts the script runtime.
- Often **302-redirects** (Apps Script web apps do this).
- Reads Spreadsheet data.

Timeout is set up to **120 seconds** for those requests. Cold or slow Google responses feel like “the site is hanging.”

### 2. Admin dashboard (`/admin-dashboard/appointments-calendar/`)

On load, PHP calls `AppointmentFeed::all()` → Apps Script `list` → full appointments from the Sheet.

There is only a **60-second local JSON cache**. Cache miss = wait for Google again. Navigating month/day still depends on that feed.

### 3. Google Meet link generation (thank-you page)

After Razorpay success:

1. Payment verify is relatively fast (Razorpay API only).
2. Finalize calls Apps Script `book`, which:
   - Writes a row to the Sheet.
   - Creates a **Google Calendar event with a Meet conference**.
3. Then PHP sends confirmation emails (SMTP).
4. The thank-you page **polls** `finalize-booking.php` every few seconds until Meet is ready (UI already warns this can take a couple of minutes).

Meet is slow because Calendar + Meet conference creation is a heavy Google API path, wrapped inside Apps Script (extra latency + cold starts), not because the marketing site is heavy.

### Summary

| Action | Main bottleneck |
| --- | --- |
| Open booking slots | Google Apps Script + Sheets (list booked / config / disabled) |
| Admin calendar load | Same Apps Script `list` of all appointments |
| Meet link after pay | Apps Script → Calendar Meet creation (+ email after) |

Local PHP + static assets are secondary.

---

## Ecommerce + customer database — recommended next stack

Sheets + PHP + Apps Script is fine for a simple booking MVP. It does **not** scale well for ecommerce catalogues, orders, inventory, customer accounts, or fast admin UX.

### Recommended direction (seamless site + shop + customers + bookings)

| Concern | Recommendation |
| --- | --- |
| App / API | **Next.js** (storefront + admin UI) **or** Next.js storefront + **Laravel / NestJS** API |
| Database | **PostgreSQL** (customers, products, orders, appointments) |
| Cache / queues | **Redis** (session/cache) + background jobs for Meet/email |
| Ecommerce | **Medusa**, **Saleor**, or **Shopify** (headless) if you want commerce faster than building from scratch |
| Payments | Keep **Razorpay** (India-friendly) |
| Appointments | Store slots/bookings in Postgres; create Meet via **Google Calendar API** from your server (or **Cal.com**) — **drop Sheets / Apps Script from the hot path** |
| Auth | Proper admin + optional customer accounts (Auth.js / Laravel Sanctum / Clerk, etc.) |
| Hosting | Vercel/Cloudflare (frontend) + managed Postgres (Neon/Supabase/RDS) + worker for emails/Meet |

### Why this is better

- Sub-second admin/booking reads from Postgres instead of multi-second Sheet round trips.
- Real customer + order history for ecommerce and CRM.
- Meet/email move to **async jobs** so payment thank-you stays fast.
- One coherent stack for marketing, shop, and consultations.

### Migration suggestion (high level)

1. Introduce Postgres + API; mirror new bookings to DB.
2. Point slot availability + admin calendar at the DB.
3. Keep Razorpay; move Meet creation to a queue worker.
4. Add ecommerce (Medusa/Shopify/custom) sharing the same customer table.
5. Retire Google Sheets as the live source of truth (optional archive only).

---

## Setup notes

1. Copy `includes/secrets.example.php` → `includes/secrets.php` and fill values.
2. Deploy Apps Script from `appointment-form/scripts/google-apps-script/` and set `apps_script_url` / secret.
3. Run locally: `php -S localhost:8080` from the repo root.
4. Do not commit `includes/secrets.php` or live `appointment-form/storage/bookings.json` / `holds.json`.

---

## Status note

This README documents the **current** PHP + Sheets architecture and the known latency sources. A full stack change for ecommerce should treat the appointment layer as the first subsystem to replace (DB + Calendar API), then add catalogue/checkout on the same backend.
