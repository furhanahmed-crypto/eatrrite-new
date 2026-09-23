"use client";

import { siteConfig } from "@/config/site";
import { Input } from "@/shared/ui/input";
import { Label } from "@/shared/ui/label";
import { Textarea } from "@/shared/ui/textarea";

const fieldClass =
  "h-11 w-full rounded-xl border border-gold/40 bg-[#fffdf8] px-3.5 text-[15px] text-brand shadow-none transition focus-visible:border-gold focus-visible:ring-gold/30 dark:bg-surface dark:text-ink";

function Field({ id, label, children }) {
  return (
    <div className="grid gap-1.5">
      <Label
        htmlFor={id}
        className="text-[12px] font-semibold tracking-[0.08em] text-brand uppercase"
      >
        {label}
      </Label>
      {children}
    </div>
  );
}

function nextQty(current, delta) {
  const qty = Number(current || 1) + delta;
  return String(
    Math.min(siteConfig.snackbarMaxQty, Math.max(siteConfig.snackbarMinQty, qty))
  );
}

export function SnackbarCheckoutFields({ form, updateField }) {

  return (
    <div className="grid gap-3.5">
      <div className="grid gap-3.5 sm:grid-cols-2">
        <Field id="sb-name" label="Full name">
          <Input
            id="sb-name"
            required
            autoComplete="name"
            placeholder="Your name"
            value={form.name}
            onChange={(e) => updateField("name", e.target.value)}
            className={fieldClass}
          />
        </Field>
        <Field id="sb-email" label="Email">
          <Input
            id="sb-email"
            type="email"
            required
            autoComplete="email"
            placeholder="you@example.com"
            value={form.email}
            onChange={(e) => updateField("email", e.target.value)}
            className={fieldClass}
          />
        </Field>
      </div>
      <Field id="sb-phone" label="Mobile number">
        <Input
          id="sb-phone"
          type="tel"
          required
          inputMode="numeric"
          maxLength={13}
          placeholder="10-digit number"
          value={form.mobilenumber}
          onChange={(e) => updateField("mobilenumber", e.target.value)}
          className={fieldClass}
        />
      </Field>
      <Field id="sb-address" label="Delivery address">
        <Textarea
          id="sb-address"
          required
          rows={4}
          placeholder="House, street, city, PIN"
          value={form.address}
          onChange={(e) => updateField("address", e.target.value)}
          className="min-h-24 rounded-xl border border-gold/40 bg-[#fffdf8] px-3.5 text-[15px] text-brand shadow-none focus-visible:border-gold focus-visible:ring-gold/30"
        />
      </Field>
      <Field id="sb-qty" label="Quantity">
        <div className="flex h-10 w-[7.5rem] shrink-0 justify-self-start items-stretch overflow-hidden rounded-md border border-gold/45 bg-white">
          <button
            type="button"
            aria-label="Fewer bars"
            onClick={() => updateField("quantity", nextQty(form.quantity, -1))}
            className="grid w-8 shrink-0 place-items-center text-sm text-brand/70 transition hover:bg-gold/10 hover:text-brand"
          >
            −
          </button>
          <input
            id="sb-qty"
            type="number"
            required
            min={siteConfig.snackbarMinQty}
            max={siteConfig.snackbarMaxQty}
            value={form.quantity}
            onChange={(e) => updateField("quantity", e.target.value)}
            className="h-full w-14 shrink-0 border-x border-gold/35 bg-transparent text-center text-sm tabular-nums text-brand outline-none"
          />
          <button
            type="button"
            aria-label="More bars"
            onClick={() => updateField("quantity", nextQty(form.quantity, 1))}
            className="grid w-8 shrink-0 place-items-center text-sm text-brand/70 transition hover:bg-gold/10 hover:text-brand"
          >
            +
          </button>
        </div>
      </Field>
    </div>
  );
}
