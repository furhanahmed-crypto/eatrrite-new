import { deleteCohortApplication } from "@/lib/db/cohort-applications";
import { isAdminAuthed } from "@/lib/admin-auth";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function POST(request) {
  try {
    if (!(await isAdminAuthed())) {
      return jsonFail(new Error("Please sign in first."), 401);
    }

    const body = await request.json();
    const id = String(body.id || "").trim();
    if (!id) {
      return jsonFail(new Error("Application id is required."), 400);
    }

    const removed = await deleteCohortApplication(id);
    return jsonOk({ ...removed, removed: true });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
