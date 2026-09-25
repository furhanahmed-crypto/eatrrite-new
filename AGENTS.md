# Agent notes — Eat Rrite PHP site

Keep changes small, PHP-only, and beginner-friendly. Do not introduce a new framework, ORM, junction tables, or frontend library.

## Architecture

- Public pages are root `*.php` files that include `includes/header.php` and `sections/`.
- Copy lives in `constants/`. Do not hard-code long marketing text in sections.
- Booking logic lives in `appointment-form/src/`. HTTP endpoints live in `appointment-form/api/`.
- Database access is PDO in `includes/db.php` plus one repository per table under `includes/db/`.
- Admin is a separate session (`er_admin_dashboard`) under `admin/`.

## Folder conventions

| Path | Use |
| --- | --- |
| `appointment-form/src/` | Domain services (slots, payment, checkout, finalize) |
| `appointment-form/api/` | Thin POST JSON endpoints |
| `appointment-form/views/` | Booking / questionnaire markup |
| `includes/db/` | SQL only |
| `constants/` | Questions and page copy |
| `admin/` | Calendar + lists — leave alone unless asked |

Prefer files around 120 lines. Split copy or CSS before inventing new layers.

## Naming

- PHP classes: `PascalCase` (`AppointmentCheckout`, `QuestionnaireRepository`)
- Methods: `camelCase`
- DB columns: `snake_case`
- Question keys: stable snake_case (`primary_goal`, `current_symptoms`)
- Public CSS/JS prefixes: `er-` for appointment, `er-q-` for questionnaire

## API / service split

- Endpoints: CSRF, JSON parse, call one service method, return JSON.
- `AppointmentService` is the facade used by APIs.
- `BookingStore` wraps `BookingRepository` / `BookingWriter`.
- Questionnaire answers go through `QuestionnaireValidator` then `QuestionnaireRepository`.
- Do not look up questionnaire rows by email. `appointment_id` is the only join.

## Database conventions

- Table is `appointments` (not `bookings`).
- Appointment statuses in the live cycle: `confirmed` → `finalizing` → `completed` (`failed` if Meet errors).
- Occupied slots and admin lists must include `confirmed`.
- `questionnaire_answers.answer` is TEXT. Multi-select = JSON array string. No extra tables.
- Add columns only when a page or query needs them. Current extras: `questionnaire_completed`, `meet_link`, `questionnaire_reminders_sent`.

## Appointment flow (do not change unless asked)

Payment verify, slot holds, 45-minute consultant blocking, cancel, hide/show slot, and admin auth stay as they are.

Lifecycle:

**Payment → confirmed appointment → questionnaire → generate Meet → save `meet_link` → show link → send existing confirmation email**

`verify-payment.php` must not create a Meet link. It redirects to `appointment-confirmed.php?appointmentId=` and emails the customer that questionnaire link only.

After questionnaire + Meet, send the Meet email to the customer and the admin booking email. Do not email admin at payment time.

## Questionnaire flow

- Questions: `constants/questionnaire.php` (merges core / symptoms / ready).
- Skip a calendar/slot question — the slot is already booked at payment.
- Required answers are validated in `QuestionnaireValidator` and again in the stepper JS.
- Saving answers replaces previous rows for that `appointment_id` and sets `questionnaire_completed = 1`.
- Reminder cron: `appointment-form/cron/questionnaire-reminders.php`. Hours live in `constants/questionnaire-reminders.php` (1, 3, 6, 12, 24, 30, 36). One email per appointment per run. Stop when `questionnaire_completed = 1`. Do not email admin for reminders.

## Google Meet flow

- Only `AppointmentCheckout::generateMeet($appointmentId)` should start Meet after questionnaire.
- Reuse `AppointmentFinalize` + `GoogleAppsScriptClient`. Do not add a second Meet client.
- Save the returned URL on the same appointment row.
- Emails stay in `email/AppointmentEmails.php`.

## Do not change casually

- Razorpay verify / create-order
- `HoldService`, slot grid, 45-minute occupancy
- Admin calendar month/day UI, hide slot, cancel booking
- Admin login / sign out
- Cohort and snackbar public forms
- Next.js folder `eatrrite-new/` (unused by PHP)

## Maintenance rules

- Reuse existing helpers (`appointment_json_ok`, `app_pdo`, `app_id`, `er_href`).
- Keep CSRF on every appointment POST API.
- Public error text stays generic for technical/Google/SMTP failures.
- After UI work, check `/appointment`, `/appointment-confirmed`, and `/admin/appointments-calendar/` for regressions.
- Never commit `includes/db.local.php` or SMTP/Razorpay secrets.
