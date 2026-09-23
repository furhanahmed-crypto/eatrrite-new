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
  confirming = false,
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
        <div className="grid grid-cols-2 gap-2 min-[380px]:grid-cols-3 sm:grid-cols-4">
          {times.map((item) => (
            <button
              key={item}
              type="button"
              disabled={confirming}
              onClick={() => onSelectTime(item)}
              className={cn(
                "h-[42px] cursor-pointer rounded-[10px] border text-[13px] font-semibold transition disabled:cursor-not-allowed disabled:opacity-60",
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
      <div className="flex flex-col-reverse gap-2 pt-1 min-[400px]:flex-row min-[400px]:items-center min-[400px]:justify-end">
        <Button type="button" variant="ghost" onClick={onCancel} disabled={confirming} className="w-full min-[400px]:w-auto">
          Cancel
        </Button>
        <Button
          type="button"
          onClick={onConfirm}
          disabled={!date || !time || confirming}
          className="w-full min-w-0 rounded-[10px] bg-brand hover:bg-brand-dark min-[400px]:w-auto min-[400px]:min-w-[140px]"
        >
          {confirming ? "Confirming…" : "Confirm slot"}
        </Button>
      </div>
    </div>
  );
}
