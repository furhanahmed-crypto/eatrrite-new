# Agent rules — Eat Rrite Next.js

## Hard limits

- Keep files under **~120 lines**.
- One component / one function = one job.
- No nested functions, nested loops, or heavy logic in UI files.
- Pages only compose sections from content arrays + components.

## Content pattern

Page content lives in `src/constants/<page>/…Content.js` as an **array of section objects** (`name`, props…). The page maps `name` → component and passes props.

## Where to change things

| Change | File |
|---|---|
| Clinic schedule / meeting minutes | `src/config/schedule.js` |
| Fee, phone, services list | `src/config/site.js` |
| Home / About / Programs copy | `src/constants/...` |
| Brand colors / fonts tokens | `src/app/globals.css` |
| Secrets | `.env.local` (see `.env.example`) |

## Structure

- Feature components: `src/components/{home,about,programs,pricing,contact,appointment,admin}/`
- Shared UI: `src/shared/components/` (Modal, ConfirmationModal, header/footer)
- Shadcn primitives: `src/shared/ui/`
- API: one concern per route under `src/app/api/`
- Lib helpers stay thin: `apps-script`, `razorpay`, `storage`, `slots`, `admin-auth`

## Do not

- Add Postgres / Redis / ecommerce in this phase.
- Talk to Sheets from the client — only via Apps Script through server routes.
- Commit `.env*`, `storage/*.json`, or real secrets.
- Touch the parent PHP site unless explicitly asked.

## Pixel match

Match the PHP site for colors (`#014e4e`, `#e5b858`), fonts (Lora + Bricolage Grotesque), spacing, and layouts. Prefer flat, beginner-friendly code over abstractions.
