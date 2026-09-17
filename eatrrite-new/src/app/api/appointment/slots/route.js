import { buildAvailability } from "@/lib/slots";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function GET() {
  try {
    const availability = await buildAvailability();
    return jsonOk(availability);
  } catch (error) {
    return jsonFail(error, 500);
  }
}
