"use client";

import { Input } from "@/shared/ui/input";
import { Label } from "@/shared/ui/label";

const fieldClass =
  "h-[40px] w-full rounded-md border-transparent bg-white px-3.5 text-[15px] text-brand shadow-sm dark:bg-surface dark:text-ink";

export function CohortApplyFields({ form, updateField }) {
  return (
    <div className="grid gap-3.5">
      <div className="grid gap-3.5 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label htmlFor="co-name" className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase">
            Full name
          </Label>
          <Input
            id="co-name"
            required
            autoComplete="name"
            maxLength={80}
            placeholder="Your name"
            value={form.name}
            onChange={(e) => updateField("name", e.target.value)}
            className={fieldClass}
          />
        </div>
        <div className="grid gap-1.5">
          <Label htmlFor="co-email" className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase">
            Email
          </Label>
          <Input
            id="co-email"
            type="email"
            required
            autoComplete="email"
            maxLength={120}
            placeholder="you@example.com"
            value={form.email}
            onChange={(e) => updateField("email", e.target.value)}
            className={fieldClass}
          />
        </div>
      </div>
      <div className="grid gap-1.5">
        <Label htmlFor="co-phone" className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase">
          Mobile number
        </Label>
        <Input
          id="co-phone"
          type="tel"
          required
          inputMode="numeric"
          maxLength={13}
          placeholder="10-digit number"
          value={form.mobilenumber}
          onChange={(e) => updateField("mobilenumber", e.target.value)}
          className={fieldClass}
        />
      </div>
    </div>
  );
}
