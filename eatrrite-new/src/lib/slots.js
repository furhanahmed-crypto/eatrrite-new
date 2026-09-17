import { blockMinutes, scheduleConfig } from "@/config/schedule";
import { availableTimesForDate } from "@/lib/schedule-slots";
import { listBookings } from "@/lib/db/bookings";
import { listDisabledSlots } from "@/lib/db/disabled-slots";
import { listActiveHolds } from "@/lib/db/holds";

function pad(n) {
  return String(n).padStart(2, "0");
}

function dateKey(date) {
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
}

function timesOnDate(rows, isoDate) {
  const times = [];
  for (const row of rows) {
    if (row.date === isoDate && row.time) times.push(row.time);
  }
  return times;
}

export async function buildAvailability(ignoreHoldId = "") {
  const booked = await listBookings();
  const disabled = await listDisabledSlots();
  const holds = await listActiveHolds(ignoreHoldId);

  const occupiedRows = [...booked, ...holds];
  const days = {};
  const from = new Date();
  from.setHours(0, 0, 0, 0);

  for (let i = 0; i <= scheduleConfig.bookingDaysAhead; i += 1) {
    const date = new Date(from);
    date.setDate(from.getDate() + i);
    const iso = dateKey(date);
    days[iso] = availableTimesForDate(
      iso,
      timesOnDate(occupiedRows, iso),
      timesOnDate(disabled, iso)
    );
  }

  const last = new Date(from);
  last.setDate(from.getDate() + scheduleConfig.bookingDaysAhead);

  return {
    timezone: scheduleConfig.timezone,
    from: dateKey(from),
    to: dateKey(last),
    days,
    customer_meeting_minutes: scheduleConfig.customerMeetingMinutes,
    consultant_block_minutes: blockMinutes(),
  };
}
