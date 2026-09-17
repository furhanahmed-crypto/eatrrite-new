"use client";

import { formatDisplayTime } from "@/lib/calendar";
import { ConfirmationModal } from "@/shared/components/ConfirmationModal";
import { useState } from "react";

export function DaySlotList({ date, rows, onSelectBooking, onToggleHidden }) {
  const [pending, setPending] = useState(null);
  const [loading, setLoading] = useState(false);

  async function confirmToggle() {
    if (!pending) return;
    setLoading(true);
    try {
      await onToggleHidden(pending.time, pending.hidden);
      setPending(null);
    } finally {
      setLoading(false);
    }
  }

  return (
    <>
      <div className="space-y-2">
        {rows.length === 0 ? (
          <p className="text-sm text-soft">No slots this day.</p>
        ) : null}
        {rows.map((row) => (
          <div
            key={`${row.kind}-${row.time}`}
            className="flex items-center justify-between gap-3 rounded-2xl border border-border-soft px-4 py-3"
          >
            <div>
              <p className="font-medium">
                {row.endTime
                  ? `${formatDisplayTime(row.time)} – ${formatDisplayTime(row.endTime)}`
                  : formatDisplayTime(row.time)}
              </p>
              {row.kind === "booking" ? (
                <button
                  type="button"
                  className="cursor-pointer text-left text-sm text-brand hover:underline"
                  onClick={() => onSelectBooking(row.events[0])}
                >
                  {row.events[0]?.name || "Booking"} · {row.events[0]?.service}
                </button>
              ) : (
                <p className="text-sm text-soft">
                  {row.kind === "disabled" ? "Hidden" : "Open"}
                </p>
              )}
            </div>
            {row.kind !== "booking" ? (
              <button
                type="button"
                className="cursor-pointer text-xs underline"
                onClick={() =>
                  setPending({ time: row.time, hidden: row.kind !== "disabled" })
                }
              >
                {row.kind === "disabled" ? "Show slot" : "Hide slot"}
              </button>
            ) : null}
          </div>
        ))}
      </div>
      <ConfirmationModal
        open={Boolean(pending)}
        onOpenChange={(open) => !open && setPending(null)}
        title={pending?.hidden ? "Hide this slot?" : "Show this slot?"}
        description="This updates the Google Sheet disabled-slots tab."
        confirmLabel={pending?.hidden ? "Hide slot" : "Show slot"}
        loading={loading}
        onConfirm={confirmToggle}
      />
    </>
  );
}
