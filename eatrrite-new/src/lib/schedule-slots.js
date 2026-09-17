import { blockMinutes, scheduleConfig } from "@/config/schedule";

const WEEKDAYS = [
  "sunday",
  "monday",
  "tuesday",
  "wednesday",
  "thursday",
  "friday",
  "saturday",
];

export function toMinutes(time) {
  const [hours, minutes] = time.split(":").map(Number);
  return hours * 60 + minutes;
}

export function minutesToTime(total) {
  const hours = Math.floor(total / 60);
  const minutes = total % 60;
  return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
}

export function weekdayName(isoDate) {
  const day = new Date(`${isoDate}T12:00:00`);
  return WEEKDAYS[day.getDay()];
}

/** All start times this weekday would offer with an empty calendar. */
export function offeredTimesForDate(isoDate) {
  const windows = scheduleConfig.weeklyHours[weekdayName(isoDate)] || [];
  const times = [];

  for (const window of windows) {
    const starts = startTimesInWindow(window);
    for (const time of starts) {
      times.push(time);
    }
  }

  return times;
}

/** 15-minute starts that fit a full customer meeting inside the window. */
export function startTimesInWindow(window) {
  const step = scheduleConfig.startIntervalMinutes;
  const meeting = scheduleConfig.customerMeetingMinutes;
  const start = toMinutes(window.start);
  const latestStart = toMinutes(window.end) - meeting;
  const times = [];

  for (let cursor = start; cursor <= latestStart; cursor += step) {
    times.push(minutesToTime(cursor));
  }

  return times;
}

/** True when two start times' consultant blocks overlap (meeting + prep). */
export function bookingsOverlap(timeA, timeB) {
  const block = blockMinutes();
  const startA = toMinutes(timeA);
  const startB = toMinutes(timeB);
  return startA < startB + block && startB < startA + block;
}

export function isBlockedByOccupied(time, occupiedTimes) {
  for (const taken of occupiedTimes) {
    if (bookingsOverlap(time, taken)) return true;
  }
  return false;
}

/** Slot start is still in the future (IST). */
export function isFutureSlot(isoDate, time) {
  const start = new Date(`${isoDate}T${time}:00+05:30`);
  return start.getTime() > Date.now();
}

/**
 * Open times for a date after overlap, disabled slots, and past filtering.
 * occupiedTimes / disabledTimes are HH:MM lists for that date only.
 */
export function availableTimesForDate(isoDate, occupiedTimes, disabledTimes) {
  const disabled = new Set(disabledTimes);
  const offered = offeredTimesForDate(isoDate);
  const open = [];

  for (const time of offered) {
    if (disabled.has(time)) continue;
    if (!isFutureSlot(isoDate, time)) continue;
    if (isBlockedByOccupied(time, occupiedTimes)) continue;
    open.push(time);
  }

  return open;
}
