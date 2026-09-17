import { listAppointments, listDisabledSlots } from "@/lib/apps-script";
import { isAdminAuthed } from "@/lib/admin-auth";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function GET() {
  try {
    if (!(await isAdminAuthed())) {
      return jsonFail(new Error("Please sign in first."), 401);
    }

    const [booked, disabled] = await Promise.all([
      listAppointments(),
      listDisabledSlots(),
    ]);

    return jsonOk({ booked, disabled });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
