"use client";

import { Button } from "@/shared/ui/button";
import {
  formatDayLabel,
  formatDisplayTime,
} from "@/components/appointment/slot-helpers";
import { cn } from "@/lib/utils";

export function SlotTimesPanel({
  date,
  times,
  time,
  onSelectTime,
  onCancel,
  onConfirm,
}) {
  return (
    <div className="space-y-4">
      <div className="border-t border-border-soft pt-4">
        <div className="mb-3 flex items-center justify-between gap-3">
          <strong className="text-brand">
            {date ? formatDayLabel(date) : "Select a date"}
          </strong>
          <span className="text-[13px] text-soft">
            {date ? (times.length ? `${times.length} slots` : "No slots") : ""}
          </span>
        </div>
        {date && times.length === 0 ? (
          <p className="text-[13px] text-soft">No open times this day.</p>
        ) : null}
        <div className="grid grid-cols-3 gap-2 sm:grid-cols-4">
          {times.map((item) => (
            <button
              key={item}
              type="button"
              onClick={() => onSelectTime(item)}
              className={cn(
                "h-[42px] cursor-pointer rounded-[10px] border text-[13px] font-semibold transition",
                time === item
                  ? "border-brand bg-brand text-white"
                  : "border-[#e3ebe0] bg-white text-brand hover:border-brand hover:bg-mint dark:bg-surface"
              )}
            >
              {formatDisplayTime(item)}
            </button>
          ))}
        </div>
      </div>
      <div className="flex items-center justify-end gap-2 pt-1">
        <Button type="button" variant="ghost" onClick={onCancel}>
          Cancel
        </Button>
        <Button
          type="button"
          onClick={onConfirm}
          disabled={!date || !time}
          className="min-w-[140px] rounded-[10px] bg-brand hover:bg-brand-dark"
        >
          Confirm slot
        </Button>
      </div>
    </div>
  );
}
