import { createHmac, timingSafeEqual } from "crypto";
import { cookies } from "next/headers";
import { getServerEnv } from "@/config/env";

export const ADMIN_COOKIE = "er_admin_session";

export function adminSessionToken() {
  const env = getServerEnv();
  if (!env.adminDashboardPassword) return "";
  return createHmac("sha256", env.adminDashboardPassword)
    .update("eatrrite-admin-v1")
    .digest("hex");
}

export function passwordsMatch(input) {
  const expected = getServerEnv().adminDashboardPassword;
  if (!expected || !input) return false;
  const a = Buffer.from(String(input));
  const b = Buffer.from(String(expected));
  if (a.length !== b.length) return false;
  return timingSafeEqual(a, b);
}

export async function isAdminAuthed() {
  const store = await cookies();
  const value = store.get(ADMIN_COOKIE)?.value || "";
  const expected = adminSessionToken();
  if (!value || !expected || value.length !== expected.length) return false;
  return timingSafeEqual(Buffer.from(value), Buffer.from(expected));
}
