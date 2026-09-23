# Agent rules — Eat Rrite Next.js

This app is **standalone**. Do not reference or modify any sibling PHP website.

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
| Clinic schedule / meeting / hold rules | `src/config/schedule.js` |
| Fee, phone, services list | `src/config/site.js` |
| Home / About / Programs copy | `src/constants/...` |
| Brand colors / fonts | `src/app/globals.css` |
| DB schema | `prisma/schema.prisma` |
| DB access | `src/lib/db/` |
| Meet links | `src/lib/meet.js` + `scripts/google-apps-script/` |
| Secrets | `.env.local` (see `.env.example`) |

## Structure

- Feature components: `src/components/{home,about,programs,pricing,contact,appointment,admin}/`
- Shared UI: `src/shared/components/` + `src/shared/ui/`
- API: one concern per route under `src/app/api/`
- Data: Neon via Prisma (`bookings`, `holds`, `disabled_slots`)
- Meet only: Apps Script (`create_meet`)

## Do not

- Import from or depend on any PHP folder.
- Commit `.env*`, or real secrets.

<!-- BEGIN:nextjs-agent-rules -->

# This is NOT the Next.js you know

This version has breaking changes — APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` (resolved from this file's directory; in monorepos the `next` package may not be visible from the repo root) before writing any code. Heed deprecation notices.

This block is written and re-added by `next dev` — verify at `node_modules/next/dist/server/lib/generate-agent-files.js`. Removing it from a diff only re-creates the uncommitted change; committing it with your work keeps the tree clean.

<!-- END:nextjs-agent-rules -->
