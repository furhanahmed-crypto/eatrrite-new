# Eat Rrite UI notes

## Direction

Warm cream pages (`#f8f5f0`), teal ink (`#014e4e`), mint highlights (`#e5fad1`). Headings use Lora / Bricolage Grotesque from the site header. Cards are white, 18–28px radius, light border, soft shadow. Pills (`pill-label` + `pill-dot`) mark section context.

Do not introduce a new component library. Match `assets/css/style.css`, `pages.css`, and `match.css`.

## Appointment page

`/appointment` is a two-column cream section:

- Left: short fee copy
- Right: booking card (`appointment-form-card--next`) with the existing `er-form` fields and slot modal

Primary actions are full-width teal buttons (`er-submit` / `er-btn-primary`). Slot picker is a modal, not an inline calendar.

## Appointment confirmation page

`/appointment-confirmed` is a 12-column grid (4 + 8) inside the same cream section.

- **Left (4/12):** “Appointment Confirmed”, one short paragraph that the questionnaire is required before Meet, then a definition list (name, service, date, time).
- **Right (8/12):** questionnaire card, then the received / Meet states in the same card.

No extra page banner. Stack to one column below 900px.

## Questionnaire layout

One question (or grouped checklist) per step. Legend is the question. Choices are large tap targets (`er-q-choice`) that turn mint when selected. Optional follow-up is a textarea under the checklist.

Keep spacing tight: 8–14px between choices, 18–22px around the progress block.

## Progress indicator

- Label: `Question N of M` plus remaining count
- 8px teal bar on a mint-grey track
- Numbered dots: grey upcoming, mint completed, teal current

## Loading / generating state

After submit, hide the form. Show:

1. “We have received your response.”
2. Existing spinner + “Generating your Google Meet link…”

Reuse `.er-spinner` and `.er-meet-link-pending` from `appointment.css`.

## Success state

When Apps Script returns a URL, replace the spinner with a teal Meet link (new tab). Hint text confirms the email is on its way. Errors stay in `.er-meet-link-error` — payment and slot stay reserved.

## Responsive behavior

- Desktop: 4/8 split, choices in a single column for readability
- Tablet/phone: summary first, form second; nav buttons stretch
- Dots wrap. Do not shrink type below 15px on inputs.

## Admin calendar

Unchanged: cream toolbar pills, month grid, 45-minute booked blocks, day slot list, Hide slot / Show slot, detail drawer. Do not restyle admin while working on the public questionnaire.

## Existing patterns to reuse

- Teal focus ring: `0 0 0 3px rgba(1, 78, 78, 0.16)`
- Inputs: 12px radius, 52px height on the booking form; questionnaire inputs can be auto height
- Ghost + primary pair for Back / Continue
- Alerts: `.er-alert` above the current step

Typography: section titles 28–40px teal; body 16–17px; uppercase micro-labels 12px, 0.04em tracking.
