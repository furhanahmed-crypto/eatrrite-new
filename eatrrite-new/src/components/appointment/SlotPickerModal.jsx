"use client";

import { useEffect, useMemo, useState } from "react";
import { Loader2 } from "lucide-react";
import { Modal } from "@/shared/components/Modal";
import { SlotCalendar } from "@/components/appointment/SlotCalendar";
import { SlotTimesPanel } from "@/components/appointment/SlotTimesPanel";
import {
  formatDayLabel,
  formatDisplayTime,
} from "@/components/appointment/slot-helpers";

export function SlotPickerModal({ open, onOpenChange, onSelect, selected }) {
  const [availability, setAvailability] = useState(null);
  const [date, setDate] = useState(selected?.date || "");
  const [time, setTime] = useState(selected?.time || "");
  const [error, setError] = useState("");
  const [cursor, setCursor] = useState(() => {
    const now = new Date();
    return { year: now.getFullYear(), month: now.getMonth() };
  });

  useEffect(() => {
    if (!open || availability) return;
    let cancelled = false;

    async function run() {
      try {
        const res = await fetch("/api/appointment/slots");
        const payload = await res.json();
        if (cancelled) return;
        if (!payload.ok) throw new Error(payload.error || "Failed to load slots");
        setAvailability(payload);
      } catch (err) {
        console.error("[appointment] slots", err);
        if (!cancelled) setError("A technical issue occurred. Please retry.");
      }
    }

    void run();
    return () => {
      cancelled = true;
    };
  }, [open, availability]);

  const availableSet = useMemo(() => {
    const set = new Set();
    Object.entries(availability?.days || {}).forEach(([key, times]) => {
      if ((times || []).length) set.add(key);
    });
    return set;
  }, [availability]);

  const times = availability?.days?.[date] || [];
  const loading = open && !availability && !error;

  function shiftMonth(delta) {
    setCursor((c) => {
      const next = new Date(c.year, c.month + delta, 1);
      return { year: next.getFullYear(), month: next.getMonth() };
    });
  }

  function handleConfirm() {
    if (!date || !time) return;
    onSelect({
      date,
      time,
      label: `${formatDayLabel(date)} · ${formatDisplayTime(time)}`,
    });
    onOpenChange(false);
  }

  return (
    <Modal
      open={open}
      onOpenChange={onOpenChange}
      title="Date and time"
      description="Choose a slot · times shown in IST"
      className="max-w-[560px] sm:max-w-[560px]"
    >
      {loading ? (
        <div className="flex min-h-48 flex-col items-center justify-center gap-3 py-8 text-soft">
          <Loader2 className="size-7 animate-spin text-brand" />
          <p className="text-sm">Loading available slots…</p>
        </div>
      ) : null}
      {error ? (
        <p className="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800">{error}</p>
      ) : null}
      {availability ? (
        <div className="space-y-4">
          <SlotCalendar
            cursor={cursor}
            onPrev={() => shiftMonth(-1)}
            onNext={() => shiftMonth(1)}
            availableSet={availableSet}
            selectedDate={date}
            onSelectDate={(iso) => {
              setDate(iso);
              setTime("");
            }}
          />
          <SlotTimesPanel
            date={date}
            times={times}
            time={time}
            onSelectTime={setTime}
            onCancel={() => onOpenChange(false)}
            onConfirm={handleConfirm}
          />
        </div>
      ) : null}
    </Modal>
  );
}
