import { blockMinutes, scheduleConfig } from "@/config/schedule";
import { offeredTimesForDate } from "@/lib/schedule-slots";

function pad(n) {
  return String(n).padStart(2, "0");
}

export function toMinutes(value) {
  const [h, m] = value.split(":").map(Number);
  return h * 60 + m;
}

export function formatDisplayTime(value) {
  const [h, m] = value.split(":").map(Number);
  const suffix = h >= 12 ? "PM" : "AM";
  const hour = ((h + 11) % 12) + 1;
  return `${hour}:${String(m).padStart(2, "0")} ${suffix}`;
}

export function addMinutes(time, minutes) {
  const total = toMinutes(time) + minutes;
  return `${pad(Math.floor(total / 60))}:${pad(total % 60)}`;
}

export function monthCells(year, month, selectedIso, byDate) {
  const first = new Date(year, month, 1);
  const start = new Date(first);
  start.setDate(1 - first.getDay());
  const today = new Date();
  const todayIso = `${today.getFullYear()}-${pad(today.getMonth() + 1)}-${pad(today.getDate())}`;
  const cells = [];

  for (let i = 0; i < 42; i += 1) {
    const cursor = new Date(start);
    cursor.setDate(start.getDate() + i);
    const iso = `${cursor.getFullYear()}-${pad(cursor.getMonth() + 1)}-${pad(cursor.getDate())}`;
    const events = byDate[iso] || [];
    cells.push({
      iso,
      day: cursor.getDate(),
      inMonth: cursor.getMonth() === month,
      isToday: iso === todayIso,
      isSelected: iso === selectedIso,
      count: events.length,
      events,
    });
  }

  return cells;
}

export function groupByDate(bookings) {
  const grouped = {};
  bookings.forEach((row) => {
    const date = row.date || "";
    if (!date) return;
    if (!grouped[date]) grouped[date] = [];
    grouped[date].push(row);
  });
  return grouped;
}

export function dayRows(date, events, disabledSet) {
  const times = offeredTimesForDate(date);
  const byStart = {};
  events.forEach((event) => {
    const time = event.time || "";
    if (!byStart[time]) byStart[time] = [];
    byStart[time].push(event);
  });

  const allTimes = [...times];
  Object.keys(byStart).forEach((time) => {
    if (!allTimes.includes(time)) allTimes.push(time);
  });
  allTimes.sort((a, b) => toMinutes(a) - toMinutes(b));

  const covered = new Set();
  const rows = [];

  for (const time of allTimes) {
    const starts = byStart[time] || [];

    if (starts.length) {
      const endTime = blockEnd(time);
      const startMinutes = toMinutes(time);
      const endMinutes = toMinutes(endTime);

      for (const other of allTimes) {
        const otherMinutes = toMinutes(other);
        if (otherMinutes > startMinutes && otherMinutes < endMinutes) {
          covered.add(other);
        }
      }

      rows.push({
        kind: "booking",
        time,
        endTime,
        events: starts,
      });
      continue;
    }

    if (covered.has(time)) continue;

    const hidden = disabledSet.has(`${date}|${time}`);
    rows.push({
      kind: hidden ? "disabled" : "open",
      time,
      endTime: null,
      events: [],
    });
  }

  return rows;
}

export function meetingEnd(time) {
  return addMinutes(time, scheduleConfig.customerMeetingMinutes);
}

export function blockEnd(time) {
  return addMinutes(time, blockMinutes());
}
