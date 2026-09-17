"use client";

function pad(n) {
  return String(n).padStart(2, "0");
}

export function formatDisplayTime(value) {
  const [h, m] = value.split(":").map(Number);
  const suffix = h >= 12 ? "PM" : "AM";
  const hour = ((h + 11) % 12) + 1;
  return `${hour}:${String(m).padStart(2, "0")} ${suffix}`;
}

export function formatDayLabel(iso) {
  const date = new Date(`${iso}T12:00:00`);
  return date.toLocaleDateString("en-IN", {
    weekday: "short",
    day: "numeric",
    month: "short",
  });
}

export function monthLabel(year, month) {
  return new Date(year, month, 1).toLocaleString("en-IN", {
    month: "long",
    year: "numeric",
  });
}

export function buildMonthCells(year, month, availableSet) {
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
    cells.push({
      iso,
      day: cursor.getDate(),
      inMonth: cursor.getMonth() === month,
      isToday: iso === todayIso,
      hasSlots: availableSet.has(iso),
    });
  }

  return cells;
}
