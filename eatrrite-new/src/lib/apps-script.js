import { getServerEnv } from "@/config/env";

export async function callAppsScript(payload) {
  const env = getServerEnv();
  if (!env.appsScriptUrl || !env.appsScriptSecret) {
    throw new Error("Apps Script is not configured.");
  }

  const body = {
    ...payload,
    secret: env.appsScriptSecret,
    sheet_id: env.googleSheetId,
    tab_name: env.googleSheetTab,
    disabled_tab_name: env.googleDisabledSlotsTab,
  };

  const response = await fetch(env.appsScriptUrl, {
    method: "POST",
    headers: { "Content-Type": "text/plain;charset=utf-8" },
    body: JSON.stringify(body),
    redirect: "follow",
  });

  const data = await response.json().catch(() => null);
  if (!data || !data.ok) {
    const message = data?.error || "Google Sheet request failed.";
    const error = new Error(message);
    error.code = data?.code || "error";
    throw error;
  }

  return data;
}

export async function listAppointments() {
  const data = await callAppsScript({ action: "list" });
  return Array.isArray(data.booked) ? data.booked : [];
}

export async function listDisabledSlots() {
  const data = await callAppsScript({ action: "list_disabled_slots" });
  return Array.isArray(data.disabled) ? data.disabled : [];
}

export async function setDisabledSlot(date, time, hidden) {
  return callAppsScript({
    action: "set_disabled_slot",
    date,
    time,
    hidden,
  });
}

export async function bookAppointment(payload) {
  return callAppsScript({ action: "book", ...payload });
}

export async function cancelAppointment(payload) {
  return callAppsScript({
    action: "cancel",
    date: payload.date,
    time: payload.time,
    phone: payload.phone || "",
    name: payload.name || "",
  });
}
