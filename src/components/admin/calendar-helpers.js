"use client";

import { useRouter } from "next/navigation";
import { dayRows, groupByDate, monthCells } from "@/lib/calendar";

function pad(n) {
  return String(n).padStart(2, "0");
}

export function todayIso() {
  const now = new Date();
  return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;
}

export async function fetchBookings() {
  const res = await fetch("/api/admin/bookings");
  const data = await res.json();
  return { res, data };
}

export function useCalendarDerived(booked, disabled, cursor, selected) {
  const byDate = groupByDate(booked);
  const disabledSet = new Set();
  disabled.forEach((row) => disabledSet.add(`${row.date}|${row.time}`));
  const cells = monthCells(cursor.year, cursor.month, selected, byDate);
  const rows = dayRows(selected, byDate[selected] || [], disabledSet);
  const monthLabel = new Date(cursor.year, cursor.month, 1).toLocaleString(
    "en-IN",
    { month: "long", year: "numeric" }
  );
  return { cells, rows, monthLabel };
}

export function useAdminActions(selected, setBooked, setDisabled) {
  const router = useRouter();

  async function reload() {
    const { res, data } = await fetchBookings();
    if (res.status === 401) {
      router.replace("/admin/login");
      return;
    }
    if (!data.ok) throw new Error(data.error || "Failed to load");
    setBooked(data.booked || []);
    setDisabled(data.disabled || []);
  }

  
  async function toggleHidden(time, hidden) {
    const res = await fetch("/api/admin/toggle-slot", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ date: selected, time, hidden }),
    });
    const data = await res.json();
    if (!data.ok) throw new Error(data.error || "Could not update slot");
    await reload();
  }

  async function cancelBooking(booking) {
    const res = await fetch("/api/admin/cancel-booking", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        date: booking.date,
        time: booking.time,
        phone: booking.phone || "",
        name: booking.name || "",
      }),
    });
    const data = await res.json();
    if (!data.ok) throw new Error(data.error || "Could not cancel booking");
    await reload();
  }

  async function logout() {
    await fetch("/api/admin/logout", { method: "POST" });
    router.replace("/admin/login");
  }

  return { reload, toggleHidden, cancelBooking, logout, router };
}
