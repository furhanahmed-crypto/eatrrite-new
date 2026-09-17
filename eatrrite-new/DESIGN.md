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

Brand CSS vars live on `:root` / `.dark` as `--er-*`. `@theme inline` maps them to Tailwind color utilities. Shadcn tokens (`primary`, `muted`, `card`…) stay separate.

## Typography

- **Headings:** Lora → `--font-heading`
- **Body:** Bricolage Grotesque → `--font-body`

Use `font-[family-name:var(--font-heading)]` for display titles. Body inherits from the theme.

## Layout helpers

- `.container-shell` — max-width content width
- `.section-space` — vertical section padding
- `.eyebrow` — small uppercase label

## Components

| Use shadcn for | Keep custom Tailwind for |
|---|---|
| Button, Input, Label, Select | Hero carousel, about splits, program grids |
| Dialog → `Modal` / `ConfirmationModal` | Page banners, float Call/WhatsApp |
| Sheet → admin booking detail | Marketing section layouts |
| Dropdown (theme / nav extras) | Pricing consultation CTAs |

Shared wrappers:

- `src/shared/components/Modal.jsx`
- `src/shared/components/ConfirmationModal.jsx`
- `src/shared/components/SiteHeader.jsx` — nav + theme toggle
- `src/shared/components/SiteFooter.jsx`
- `src/shared/components/FloatActions.jsx`

## Motion

Keep motion subtle and purposeful (hero/carousel, theme transitions). Avoid decorative noise.

## Imagery

Images live under `public/images/` with the same subfolders as the PHP `assets/images/` tree (hero, about, logo, blog, faq, services, testimonials, …).
