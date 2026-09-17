# Eat Rrite (Next.js)

Standalone marketing site + appointment booking for Eat Rrite.

## Stack

- Next.js App Router + React
- Tailwind CSS 4 + shadcn/ui
- Neon Postgres + Prisma
- Razorpay
- Google Apps Script (Meet links only)

## Setup

```bash
bun install
cp .env.example .env.local
# Fill Razorpay, admin password, mail, Apps Script URL/secret
bunx neon auth
bunx neon link --project-id sparkling-king-56603914 --branch production -y
bunx neon env pull --file .env.local
bun run db:push
bun run dev
```

Deploy Meet script from `scripts/google-apps-script/` (see that folder’s README).

## Optional: import old bookings CSV

1. Save a CSV as `prisma/import/bookings.csv`
2. `bun run db:import-bookings`

## Booking flow

1. Confirm slot → Neon `holds` (15 min)
2. Pay → Neon `bookings`
3. Finalize → Apps Script Meet → save `meet_link`

## Where to edit

| What | Where |
|---|---|
| Hours / slot rules | `src/config/schedule.js` |
| Site copy / fee | `src/config/site.js`, `src/constants/` |
| Schema | `prisma/schema.prisma` |
| DB helpers | `src/lib/db/` |
| Meet | `src/lib/meet.js`, `scripts/google-apps-script/` |
| Brand tokens | `src/app/globals.css` |
