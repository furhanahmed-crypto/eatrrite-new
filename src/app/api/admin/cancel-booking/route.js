import { deleteBooking } from "@/lib/db/bookings";
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
    const phone = String(body.phone || "").trim();
    const name = String(body.name || "").trim();

    if (!date || !time) {
      return jsonFail(new Error("Date and time are required."), 400);
    }

    const cancelled = await deleteBooking({ date, time, phone, name });
    return jsonOk({ ...cancelled, cancelled: true });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
