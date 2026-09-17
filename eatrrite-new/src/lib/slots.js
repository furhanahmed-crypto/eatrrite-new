import { blockMinutes, scheduleConfig } from "@/config/schedule";
import { availableTimesForDate } from "@/lib/schedule-slots";
import { listAppointments, listDisabledSlots } from "@/lib/apps-script";
import { readHolds } from "@/lib/storage";

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

export async function buildAvailability() {
  const booked = await listAppointments().catch(() => []);
  const disabled = await listDisabledSlots().catch(() => []);
  const holds = await readHolds();
  const now = Date.now();
  const activeHolds = holds.filter((row) => row.expiresAt > now);

  const occupiedRows = [...booked, ...activeHolds];
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
