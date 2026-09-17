"use client";

import { ChevronDown } from "lucide-react";
import { siteConfig } from "@/config/site";
import { Input } from "@/shared/ui/input";
import { Label } from "@/shared/ui/label";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/shared/ui/dropdown-menu";
import { cn } from "@/lib/utils";

const fieldClass =
  "h-[40px] w-full rounded-md border-transparent bg-white px-3.5 text-[15px] text-brand shadow-sm dark:bg-surface dark:text-ink";

export function AppointmentFields({ form, updateField, slot, onOpenSlots }) {
  return (
    <div className="grid gap-3.5">
      <div className="grid gap-1.5">
        <Label className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase">
          Service
        </Label>
        <DropdownMenu>
          <DropdownMenuTrigger
            className={cn(
              fieldClass,
              "inline-flex cursor-pointer items-center justify-between outline-none focus-visible:border-brand focus-visible:ring-3 focus-visible:ring-brand/20"
            )}
          >
            <span className={form.programname ? "text-brand" : "text-soft"}>
              {form.programname || "Select a service"}
            </span>
            <ChevronDown className="size-4 text-soft" />
          </DropdownMenuTrigger>
          <DropdownMenuContent className="w-(--anchor-width) max-w-[min(100vw-2rem,28rem)]">
            {siteConfig.services.map((service) => (
              <DropdownMenuItem
                key={service}
                onClick={() => updateField("programname", service)}
              >
                {service}
              </DropdownMenuItem>
            ))}
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <div className="grid gap-3.5 sm:grid-cols-2">
        <div className="grid gap-1.5">
          <Label
            htmlFor="name"
            className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase"
          >
            Full name
          </Label>
          <Input
            id="name"
            required
            placeholder="Your name"
            autoComplete="name"
            value={form.name}
            onChange={(e) => updateField("name", e.target.value)}
            className={fieldClass}
          />
        </div>
        <div className="grid gap-1.5">
          <Label
            htmlFor="email"
            className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase"
          >
            Email address
          </Label>
          <Input
            id="email"
            type="email"
            required
            placeholder="you@example.com"
            autoComplete="email"
            value={form.email}
            onChange={(e) => updateField("email", e.target.value)}
            className={fieldClass}
          />
        </div>
      </div>

      <div className="grid gap-1.5">
        <Label
          htmlFor="phone"
          className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase"
        >
          Mobile number
        </Label>
        <Input
          id="phone"
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

      <button
        type="button"
        onClick={onOpenSlots}
        className={cn(
          "flex min-h-16 w-full cursor-pointer flex-col items-start gap-1 rounded-xl border border-dashed border-brand/35 bg-white/70 px-3.5 py-2.5 text-left transition hover:border-solid hover:border-brand hover:bg-white dark:bg-surface",
          slot ? "border-solid border-brand bg-white" : ""
        )}
      >
        <span className="text-[12px] font-semibold tracking-[0.04em] text-body uppercase">
          Appointment slot
        </span>
        <span
          className={cn(
            "text-[15px]",
            slot ? "font-semibold text-brand" : "font-medium text-soft"
          )}
        >
          {slot?.label || "Select date and time"}
        </span>
      </button>
    </div>
  );
}
