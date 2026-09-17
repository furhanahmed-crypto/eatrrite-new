import { scheduleConfig } from "@/config/schedule";
import { availableTimesForDate } from "@/lib/schedule-slots";
import { listBookings } from "@/lib/db/bookings";
import { listDisabledSlots } from "@/lib/db/disabled-slots";
import { listActiveHolds, upsertHold } from "@/lib/db/holds";

function timesOnDate(rows, isoDate) {
  const times = [];
  for (const row of rows) {
    if (row.date === isoDate && row.time) times.push(row.time);
  }
  return times;
}

/** True when date+time is free, optionally ignoring one hold (the caller's). */
export async function isSlotFree(date, time, ignoreHoldId = "") {
  const booked = await listBookings();
  const disabled = await listDisabledSlots();
  const holds = await listActiveHolds(ignoreHoldId);
  const open = availableTimesForDate(
    date,
    timesOnDate([...booked, ...holds], date),
    timesOnDate(disabled, date)
  );
  return open.includes(time);
}

/** Hold a slot on selection for holdMinutes. */
export async function placeSelectionHold(date, time, holdId) {
  const free = await isSlotFree(date, time, holdId);
  if (!free) {
    throw new Error("That slot is no longer available. Pick another time.");
  }
  return upsertHold({ holdId, date, time, orderId: "" });
}

/** Attach a Razorpay order to an existing selection hold (or create one). */
export async function attachOrderHold(date, time, holdId, orderId) {
  const free = await isSlotFree(date, time, holdId);
  if (!free) {
    throw new Error("That slot is no longer available.");
  }
  return upsertHold({ holdId, date, time, orderId });
}

export { listActiveHolds };
export const holdMinutes = () => scheduleConfig.holdMinutes;
