/**
 * Apps Script is Meet-only. All booking data lives in Neon via Prisma.
 */
import { getServerEnv } from "@/config/env";

export async function createMeetLink(payload) {
  const env = getServerEnv();
  if (!env.appsScriptUrl || !env.appsScriptSecret) {
    throw new Error("Apps Script is not configured for Meet links.");
  }

  const response = await fetch(env.appsScriptUrl, {
    method: "POST",
    headers: { "Content-Type": "text/plain;charset=utf-8" },
    body: JSON.stringify({
      action: "create_meet",
      secret: env.appsScriptSecret,
      name: payload.name || "",
      service: payload.service || "",
      phone: payload.phone || "",
      payment_id: payload.paymentId || "",
      start_iso: payload.startIso,
      end_iso: payload.endIso,
    }),
    redirect: "follow",
  });

  const data = await response.json().catch(() => null);
  if (!data || !data.ok || !data.meet_link) {
    const error = new Error(data?.error || "Could not create Google Meet link.");
    error.code = data?.code || "error";
    throw error;
  }

  return {
    meet_link: data.meet_link,
    booked_at: data.booked_at || "",
  };
}
