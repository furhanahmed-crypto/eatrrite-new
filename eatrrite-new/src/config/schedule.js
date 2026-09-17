/**
 * All appointment booking rules + weekly hours.
 * Edit this file only — slot picker, holds, finalize, and admin calendar read from here.
 */
export const scheduleConfig = {
  timezone: "Asia/Kolkata",

  // How far ahead customers can book
  bookingDaysAhead: 30,

  // How long a unpaid checkout holds a slot (minutes)
  holdMinutes: 15,

  // Customer-facing meeting length
  customerMeetingMinutes: 30,

  // Private consultant buffer after each meeting (blocks the next starts)
  consultantPrepMinutes: 15,

  // Gap between offered start times
  startIntervalMinutes: 15,

  // Empty day = closed. Windows are [start, end) for the customer meeting.
  weeklyHours: {
    monday: [
      { start: "11:30", end: "14:00" },
      { start: "15:30", end: "17:30" },
      { start: "20:30", end: "21:30" },
    ],
    tuesday: [
      { start: "11:30", end: "14:00" },
      { start: "15:30", end: "17:30" },
      { start: "20:30", end: "21:30" },
    ],
    wednesday: [
      { start: "11:30", end: "14:00" },
      { start: "15:30", end: "17:30" },
      { start: "20:30", end: "21:30" },
    ],
    thursday: [
      { start: "11:30", end: "14:00" },
      { start: "15:30", end: "17:30" },
      { start: "20:30", end: "21:30" },
    ],
    friday: [],
    saturday: [{ start: "11:30", end: "14:30" }],
    sunday: [{ start: "11:30", end: "13:00" }],
  },
};

/** Meeting + prep — one booking blocks this long on the calendar. */
export function blockMinutes() {
  return (
    scheduleConfig.customerMeetingMinutes +
    scheduleConfig.consultantPrepMinutes
  );
}

export function publicHoursNote() {
  return "Mon–Thu 11:30 AM–2:00 PM, 3:30 PM–5:30 PM, 8:30 PM–9:30 PM · Fri unavailable · Sat 11:30 AM–2:30 PM · Sun 11:30 AM–1:00 PM";
}
