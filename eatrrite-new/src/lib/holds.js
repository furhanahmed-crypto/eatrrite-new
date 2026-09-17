import { scheduleConfig } from "@/config/schedule";
import { availableTimesForDate } from "@/lib/schedule-slots";
import { listAppointments, listDisabledSlots } from "@/lib/apps-script";
import { readHolds, saveHolds } from "@/lib/storage";

function timesOnDate(rows, isoDate) {
  const times = [];
  for (const row of rows) {
    if (row.date === isoDate && row.time) times.push(row.time);
  }
  return times;
}

/** Drop expired holds and return the rest. */
export async function activeHolds(ignoreHoldId = "") {
  const now = Date.now();
  const rows = await readHolds();
  const kept = [];

  for (const row of rows) {
    if (row.expiresAt <= now) continue;
    if (ignoreHoldId && row.holdId === ignoreHoldId) continue;
    kept.push(row);
  }

  return kept;
}

export async function saveActiveHolds(rows) {
  const now = Date.now();
  const kept = rows.filter((row) => row.expiresAt > now);
  await saveHolds(kept);
  return kept;
}

/** True when date+time is free, optionally ignoring one hold (the caller's). */
export async function isSlotFree(date, time, ignoreHoldId = "") {
  const booked = await listAppointments().catch(() => []);
  const disabled = await listDisabledSlots().catch(() => []);
  const holds = await activeHolds(ignoreHoldId);
  const open = availableTimesForDate(
    date,
    timesOnDate([...booked, ...holds], date),
    timesOnDate(disabled, date)
  );
  return open.includes(time);
}

/** Hold a slot on selection for holdMinutes. Replaces any prior hold by the same holdId. */
export async function placeSelectionHold(date, time, holdId) {
  const free = await isSlotFree(date, time, holdId);
  if (!free) {
    throw new Error("That slot is no longer available. Pick another time.");
  }

  const expiresAt = Date.now() + scheduleConfig.holdMinutes * 60 * 1000;
  const others = await activeHolds(holdId);
  others.push({ date, time, holdId, orderId: "", expiresAt });
  await saveActiveHolds(others);

  return { holdId, date, time, expiresAt };
}

/** Attach a Razorpay order to an existing selection hold (or create one). */
export async function attachOrderHold(date, time, holdId, orderId) {
  const free = await isSlotFree(date, time, holdId);
  if (!free) {
    throw new Error("That slot is no longer available.");
  }

  const expiresAt = Date.now() + scheduleConfig.holdMinutes * 60 * 1000;
  const others = await activeHolds(holdId);
  others.push({ date, time, holdId, orderId, expiresAt });
  await saveActiveHolds(others);

  return { holdId, orderId, expiresAt };
}
