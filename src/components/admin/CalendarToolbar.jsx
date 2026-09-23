"use client";

import { Button } from "@/shared/ui/button";

export function CalendarToolbar({ monthLabel, onPrev, onNext }) {
  return (
    <div className="space-y-4">
      <h1 className="font-heading text-2xl leading-tight min-[400px]:text-3xl">
        Consultations
      </h1>
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
