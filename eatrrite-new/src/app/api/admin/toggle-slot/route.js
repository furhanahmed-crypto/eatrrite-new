import { setDisabledSlot } from "@/lib/db/disabled-slots";
import { offeredTimesForDate } from "@/lib/schedule-slots";
import { isAdminAuthed } from "@/lib/admin-auth";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function POST(request) {
  try {
    if (!(await isAdminAuthed())) {
      return jsonFail(new Error("Please sign in first."), 401);
    }

    const body = await request.json();
    const date = String(body.date || "").trim();
    const time = String(body.time || "").trim();
    const hidden = Boolean(body.hidden);

    const times = offeredTimesForDate(date);
    if (!times.includes(time)) {
      return jsonFail(new Error("That time is not a slot on this day."), 400);
    }

    const result = await setDisabledSlot(date, time, hidden);
    return jsonOk(result);
  } catch (error) {
    return jsonFail(error, 500);
  }
}
