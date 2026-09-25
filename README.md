# Eat Rrite

PHP marketing site and booking system for Eat Rrite — holistic nutrition coaching (Hyderabad & Dehradun, online consultations).

## Tech stack

- PHP 8 + HTML includes (no framework)
- MySQL (Hostinger) via PDO
- Custom CSS (`assets/css/`) and small vanilla JS
- Razorpay for the consultation confirmation fee
- Google Apps Script for Google Meet / Calendar
- PHPMailer + SMTP for confirmation emails

There is a leftover Next.js app under `eatrrite-new/`. Production and local PHP work from this repo root, not that folder.

## Main features

- Public pages: home, about, programs, pricing, blog, contact, cohort, snackbar
- Paid appointment booking with slot holds
- Post-payment transformation questionnaire
- Google Meet generation after the questionnaire
- Admin calendar, hide/show slots, cancel booking

## Project structure

```
/
├── index.php, about.php, programs.php, appointment.php
├── appointment-confirmed.php     # questionnaire + Meet
├── constants/                    # page copy, questionnaire questions
├── sections/                     # marketing sections
├── includes/                     # config, header/footer, PDO, repositories
├── appointment-form/             # booking APIs, views, JS
├── admin/                        # consultation calendar
├── cohort-form/, snackbar-form/
├── email/                        # PHPMailer templates
└── sql/                          # MySQL schema + questionnaire migration
```

## Appointment flow

1. Customer picks a service, details, and slot on `/appointment`.
2. PHP creates a Razorpay order and holds the slot.
3. After payment verify, the row is saved as `confirmed` with an empty Meet link. The customer is emailed the questionnaire URL immediately.
4. Browser redirects to `/appointment-confirmed?appointmentId=…`. If that appointment already has answers, the form is skipped and the Meet link is shown.
5. Customer completes the questionnaire once. Answers are stored by `appointment_id`.
6. PHP calls Apps Script to create the Meet link, saves it on the same appointment, emails the Meet link to the customer, and emails admin.

Slot occupancy treats `confirmed`, `verified`, `finalizing`, and `completed` as taken. Consultant blocks are 45 minutes (30-minute meeting + 15-minute prep).

## Payment flow

- `create-order.php` validates the booking, holds the slot, creates a Razorpay order.
- Checkout runs in the browser.
- `verify-payment.php` checks the Razorpay signature and inserts the appointment.
- Payment success does **not** create a Meet link.

## Questionnaire flow

- Questions live in `constants/questionnaire*.php`.
- UI is a stepped form: 4/12 appointment summary, 8/12 questions.
- `submit-questionnaire.php` validates required answers and writes `questionnaire_answers`.
- Multi-select answers are stored as a JSON array in the `answer` text column.
- `appointments.questionnaire_completed` is set to `1`.
- The appointment email/name already stored on the appointment remain the source of truth.
- If the form stays unfilled, a cron emails reminders at 1h, 3h, 6h, 12h, 24h, 30h and 36h after payment. Reminders stop as soon as the form is submitted.

On Hostinger, run every 15 minutes:

```
php /home/USER/domains/YOUR-DOMAIN/public_html/appointment-form/cron/questionnaire-reminders.php
```

or hit `appointment-form/cron/questionnaire-reminders.php?key=YOUR_CRON_SECRET`. Set `public_base_url` in `includes/db.local.php` so CLI emails use the live questionnaire link. SQL: `sql/reminders.mysql.sql`.

## Google Meet generation

- Triggered only after the questionnaire is saved.
- `generate-meet.php` → `AppointmentCheckout::generateMeet()` → `AppointmentFinalize` → Apps Script.
- Meet URL is written to `appointments.meet_link`.
- Existing confirmation emails then go out with the appointment details and Meet link.

## Admin flow

- `/admin` redirects to `/admin/appointments-calendar/`.
- Login uses `admin_password` from `includes/db.local.php` (empty password opens the dashboard on local).
- Calendar month/day views, hide/show slot, cancel booking are unchanged.

## Database overview

Live tables (see `sql/schema.mysql.sql`):

- `appointments` — bookings, payment ids, `questionnaire_completed`, `questionnaire_reminders_sent`, `meet_link`
- `questionnaire_answers` — one row per question, keyed by `appointment_id`
- `holds` — temporary slot holds during checkout
- `disabled_slots` — admin-hidden slots
- `snackbar_orders`, `cohort_applications`

On an existing database that already has `appointments`, run `sql/questionnaire.mysql.sql` then `sql/reminders.mysql.sql`.

## Important APIs

| URL | Role |
| --- | --- |
| `appointment-form/api/slots.php` | Available times |
| `appointment-form/api/create-order.php` | Razorpay order + hold |
| `appointment-form/api/verify-payment.php` | Signature check + confirmed row |
| `appointment-form/api/submit-questionnaire.php` | Save answers |
| `appointment-form/api/generate-meet.php` | Meet + email |
| `appointment-form/api/finalize-booking.php` | Legacy finalize (now requires questionnaire) |

## Local setup

1. Copy `includes/db.local.example.php` → `includes/db.local.php` and fill Hostinger MySQL, Razorpay, Apps Script, and SMTP values.
2. Allow your public IP under Hostinger → Remote MySQL. Local `host` is the Hostinger hostname, not `localhost`.
3. Import `sql/schema.mysql.sql` (new DB) or `sql/questionnaire.mysql.sql` plus `sql/reminders.mysql.sql` (existing DB).
4. From the repo root: `php -S localhost:8080`
5. Open http://localhost:8080/appointment.php and http://localhost:8080/admin

Do not commit `includes/db.local.php`, `includes/secrets.php`, or live credentials.
