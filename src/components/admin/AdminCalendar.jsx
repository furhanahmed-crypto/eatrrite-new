"use client";

import { useEffect, useState } from "react";
import { MonthGrid } from "@/components/admin/MonthGrid";
import { DaySlotList } from "@/components/admin/DaySlotList";
import { BookingDetailSheet } from "@/components/admin/BookingDetailSheet";
import { CalendarToolbar } from "@/components/admin/CalendarToolbar";
import { AdminCalendarSkeleton } from "@/components/admin/AdminCalendarSkeleton";
import {
  fetchBookings,
  todayIso,
  useAdminActions,
  useCalendarDerived,
} from "@/components/admin/calendar-helpers";

export function AdminCalendar() {
  const [cursor, setCursor] = useState(() => {
    const now = new Date();
    return { year: now.getFullYear(), month: now.getMonth() };
  });
  const [selected, setSelected] = useState(todayIso);
  const [booked, setBooked] = useState([]);
  const [disabled, setDisabled] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [booking, setBooking] = useState(null);
  const { cells, rows, monthLabel } = useCalendarDerived(
    booked,
    disabled,
    cursor,
    selected
  );
  const { toggleHidden, cancelBooking, router } = useAdminActions(
    selected,
    setBooked,
    setDisabled
  );

  useEffect(() => {
    let alive = true;

    async function run() {
      try {
        const { res, data } = await fetchBookings();
        if (!alive) return;
        if (res.status === 401) {
          router.replace("/admin/login");
          return;
        }
        if (!data.ok) throw new Error(data.error || "Failed to load");
        setBooked(data.booked || []);
        setDisabled(data.disabled || []);
      } catch (err) {
        console.error("[admin] load", err);
        if (alive) setError("A technical issue occurred. Please retry.");
      } finally {
        if (alive) setLoading(false);
      }
    }

    void run();
    return () => {
      alive = false;
    };
  }, [router]);

  function shiftMonth(delta) {
    setCursor((c) => {
      const d = new Date(c.year, c.month + delta, 1);
      return { year: d.getFullYear(), month: d.getMonth() };
    });
  }

  if (loading) return <AdminCalendarSkeleton />;

  return (
    <div className="space-y-5 min-[400px]:space-y-6">
      <CalendarToolbar
        monthLabel={monthLabel}
        onPrev={() => shiftMonth(-1)}
        onNext={() => shiftMonth(1)}
      />
      {error ? <p className="text-destructive">{error}</p> : null}
      <MonthGrid cells={cells} onSelectDate={setSelected} />
      <div className="min-w-0">
        <h2 className="mb-3 text-base font-medium min-[400px]:text-lg">
          Day · {selected}
        </h2>
        <DaySlotList
          date={selected}
          rows={rows}
          onSelectBooking={setBooking}
          onToggleHidden={toggleHidden}
        />
      </div>
      <BookingDetailSheet
        open={Boolean(booking)}
        onOpenChange={(open) => !open && setBooking(null)}
        booking={booking}
        onCancelBooking={cancelBooking}
      />
    </div>
  );
}
