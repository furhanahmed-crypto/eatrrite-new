"use client";

export function MonthGrid({ cells, onSelectDate }) {
  const labels = [
    { short: "S", full: "Sun" },
    { short: "M", full: "Mon" },
    { short: "T", full: "Tue" },
    { short: "W", full: "Wed" },
    { short: "T", full: "Thu" },
    { short: "F", full: "Fri" },
    { short: "S", full: "Sat" },
  ];

  return (
    <div className="min-w-0">
      <div className="mb-2 grid grid-cols-7 gap-0.5 text-center text-[10px] text-soft min-[400px]:gap-1 min-[400px]:text-xs">
        {labels.map((label, index) => (
          <div key={`${label.full}-${index}`}>
            <span className="min-[400px]:hidden">{label.short}</span>
            <span className="hidden min-[400px]:inline">{label.full}</span>
          </div>
        ))}
      </div>
      <div className="grid grid-cols-7 gap-0.5 min-[400px]:gap-1">
        {cells.map((cell) => (
          <button
            key={cell.iso}
            type="button"
            onClick={() => onSelectDate(cell.iso)}
            title={cell.count > 0 ? `${cell.count} booked` : undefined}
            className={`flex min-h-11 min-w-0 cursor-pointer flex-col items-start rounded-lg border p-1 text-left text-xs transition hover:border-brand/40 min-[400px]:min-h-14 min-[400px]:rounded-xl min-[400px]:p-2 min-[400px]:text-sm ${
              cell.isSelected
                ? "border-brand bg-mint"
                : "border-border-soft"
            } ${cell.inMonth ? "" : "opacity-40"} ${
              cell.isToday ? "ring-1 ring-gold" : ""
            }`}
          >
            <span className="font-medium leading-none">{cell.day}</span>
            {cell.count > 0 ? (
              <span className="mt-0.5 block w-full truncate text-[10px] leading-tight text-brand min-[400px]:mt-1 min-[400px]:text-xs">
                <span className="min-[400px]:hidden">{cell.count}</span>
                <span className="hidden min-[400px]:inline">
                  {cell.count} booked
                </span>
              </span>
            ) : null}
          </button>
        ))}
      </div>
    </div>
  );
}
