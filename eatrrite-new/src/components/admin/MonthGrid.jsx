"use client";

export function MonthGrid({ cells, onSelectDate }) {
  const labels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  return (
    <div>
      <div className="mb-2 grid grid-cols-7 gap-1 text-center text-xs text-soft">
        {labels.map((label) => (
          <div key={label}>{label}</div>
        ))}
      </div>
      <div className="grid grid-cols-7 gap-1">
        {cells.map((cell) => (
          <button
            key={cell.iso}
            type="button"
            onClick={() => onSelectDate(cell.iso)}
            className={`min-h-16 cursor-pointer rounded-xl border p-2 text-left text-sm transition hover:border-brand/40 ${
              cell.isSelected
                ? "border-brand bg-mint"
                : "border-border-soft"
            } ${cell.inMonth ? "" : "opacity-40"} ${
              cell.isToday ? "ring-1 ring-gold" : ""
            }`}
          >
            <span className="font-medium">{cell.day}</span>
            {cell.count > 0 ? (
              <span className="mt-1 block text-xs text-brand">
                {cell.count} booked
              </span>
            ) : null}
          </button>
        ))}
      </div>
    </div>
  );
}
