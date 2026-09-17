# Design — Eat Rrite

## Brand utilities (use these — not `text-[var(--…)]`)

| Utility | Meaning |
|---|---|
| `text-ink` / `bg-ink` | Headings / strong text |
| `text-soft` | Muted supporting text |
| `text-body` | Body copy color |
| `bg-cream` / `bg-mint` / `bg-surface` | Section & card surfaces |
| `bg-brand` / `text-brand` | Teal brand |
| `bg-gold` / `text-gold` | Accent gold |
| `border-border-soft` | Soft borders |
| `shadow-er` | Soft brand shadow |
| `font-heading` | Lora |

Brand CSS vars live on `:root` / `.dark` as `--er-*`. `@theme inline` maps them to Tailwind color utilities.

## Typography

- **Headings:** Lora → `--font-heading`
- **Body:** Bricolage Grotesque → `--font-body`

## Layout helpers

- `.container-er` — max-width content width

## Components

| Use shadcn for | Keep custom Tailwind for |
|---|---|
| Button, Input, Label, Dialog, Sheet | Hero, page banner, home sections |
| Dropdown | Marketing layouts |

Shared: `SiteHeader`, `SiteFooter`, `PageBanner`, `Modal`, `ConfirmationModal`, `FloatActions`.

## Motion

Subtle and purposeful (hero, reveal, split titles). Avoid decorative noise.

## Imagery

Static assets under `public/images/` (`hero`, `about`, `logo`, `blog`, `faq`, `services`, `testimonials`, …).
