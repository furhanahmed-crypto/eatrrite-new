import { placeSelectionHold } from "@/lib/holds";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { randomUUID } from "crypto";

export async function POST(request) {
  try {
    const body = await request.json();
    const date = String(body.date || "").trim();
    const time = String(body.time || "").trim();
    const holdId = String(body.holdId || "").trim() || randomUUID();

    if (!date || !time) {
      return jsonFail(new Error("Choose a date and time first."), 400);
    }

    const hold = await placeSelectionHold(date, time, holdId);
    return jsonOk(hold);
  } catch (error) {
    const status = /no longer available/i.test(error.message || "") ? 409 : 500;
    return jsonFail(error, status);
  }
}
