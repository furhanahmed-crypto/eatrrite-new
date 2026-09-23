import { listCohortApplications } from "@/lib/db/cohort-applications";
import { isAdminAuthed } from "@/lib/admin-auth";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function GET() {
  try {
    if (!(await isAdminAuthed())) {
      return jsonFail(new Error("Please sign in first."), 401);
    }

    const applications = await listCohortApplications();
    return jsonOk({ applications });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
