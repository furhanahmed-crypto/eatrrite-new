import { buildAvailability } from "@/lib/slots";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function GET(request) {
  try {
    const holdId = request.nextUrl.searchParams.get("holdId") || "";
    const availability = await buildAvailability(holdId);
    return jsonOk(availability);
  } catch (error) {
    return jsonFail(error, 500);
  }
}
