"use client";

import { Button } from "@/shared/ui/button";

export function CalendarToolbar({ monthLabel, onPrev, onNext, onLogout }) {
  return (
    <div className="space-y-4">
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <p className="text-xs uppercase tracking-[0.2em] text-brand">
            Admin
          </p>
          <h1 className="font-heading text-3xl">
            Appointments
          </h1>
        </div>
        <Button type="button" variant="outline" onClick={onLogout}>
          Sign out
        </Button>
      </div>
      <div className="flex items-center justify-between">
        <Button type="button" variant="outline" onClick={onPrev}>
          Prev
        </Button>
        <p className="font-medium">{monthLabel}</p>
        <Button type="button" variant="outline" onClick={onNext}>
          Next
        </Button>
      </div>
    </div>
  );
}
