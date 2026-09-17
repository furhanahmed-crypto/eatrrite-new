"use client";

import { Button } from "@/shared/ui/button";

export function CalendarToolbar({ monthLabel, onPrev, onNext, onLogout }) {
  return (
    <div className="space-y-4">
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <p className="text-[10px] uppercase tracking-[0.2em] text-brand min-[400px]:text-xs">
            Admin
          </p>
          <h1 className="font-heading text-2xl leading-tight min-[400px]:text-3xl">
            Appointments
          </h1>
        </div>
        <Button
          type="button"
          variant="outline"
          onClick={onLogout}
          className="shrink-0"
        >
          Sign out
        </Button>
      </div>
      <div className="flex items-center justify-between gap-2">
        <Button type="button" variant="outline" size="sm" onClick={onPrev}>
          Prev
        </Button>
        <p className="min-w-0 truncate text-center text-sm font-medium min-[400px]:text-base">
          {monthLabel}
        </p>
        <Button type="button" variant="outline" size="sm" onClick={onNext}>
          Next
        </Button>
      </div>
    </div>
  );
}
