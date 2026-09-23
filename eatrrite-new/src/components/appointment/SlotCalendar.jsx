"use client";

import { ChevronLeft, ChevronRight } from "lucide-react";
import { buildMonthCells, monthLabel } from "@/components/appointment/slot-helpers";
import { cn } from "@/lib/utils";

const weekdays = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

export function SlotCalendar({
  cursor,
  onPrev,
  onNext,
  availableSet,
  selectedDate,
  onSelectDate,
}) {
  const cells = buildMonthCells(cursor.year, cursor.month, availableSet);

  return (
    <div>
      <div className="flex items-center justify-between gap-3">
        <button
          type="button"
          onClick={onPrev}
          aria-label="Previous month"
          className="grid size-9 cursor-pointer place-items-center rounded-[10px] bg-mint text-brand"
        >
          <ChevronLeft className="size-5" />
        </button>
        <h3 className="font-heading text-lg text-brand md:text-[22px]">
          {monthLabel(cursor.year, cursor.month)}
        </h3>
        <button
          type="button"
          onClick={onNext}
          aria-label="Next month"
          className="grid size-9 cursor-pointer place-items-center rounded-[10px] bg-mint text-brand"
        >
          <ChevronRight className="size-5" />
        </button>
      </div>
      <div className="mt-3 grid grid-cols-7 gap-1">
        {weekdays.map((day) => (
          <span
            key={day}
            className="text-center text-[10px] font-semibold text-soft min-[400px]:text-[11px]"
          >
            {day}
          </span>
        ))}
      </div>
      <div className="mt-2 grid grid-cols-7 gap-1">
        {cells.map((cell) => {
          const selected = selectedDate === cell.iso;
          const enabled = cell.inMonth && cell.hasSlots;
          return (
            <button
              key={cell.iso}
              type="button"
              disabled={!enabled}
              onClick={() => onSelectDate(cell.iso)}
              className={cn(
                "h-9 rounded-lg text-sm font-semibold transition min-[400px]:h-[42px] min-[400px]:rounded-xl",
                !cell.inMonth && "text-soft/40",
                cell.inMonth && !enabled && "cursor-not-allowed text-soft/50",
                enabled && "cursor-pointer",
                enabled && !selected && "text-brand hover:bg-[#e5fad1]",
                cell.isToday && !selected && "ring-1 ring-brand/25",
                selected && "bg-brand text-white hover:bg-brand"
              )}
            >
              {cell.day}
            </button>
          );
        })}
      </div>
    </div>
  );
}
