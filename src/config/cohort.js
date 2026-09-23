import { siteConfig } from "@/config/site";

export function getIstDate(now = new Date()) {
  const parts = new Intl.DateTimeFormat("en-GB", {
    timeZone: siteConfig.timezone,
    year: "numeric",
    month: "2-digit",
    day: "numeric",
  }).formatToParts(now);
  const map = Object.fromEntries(parts.map((part) => [part.type, part.value]));
  return {
    year: Number(map.year),
    month: Number(map.month),
    day: Number(map.day),
  };
}

export function currentCohortMonth(now = new Date()) {
  const { year, month } = getIstDate(now);
  return `${year}-${String(month).padStart(2, "0")}`;
}

export function validateCohortApplicant(input) {
  const name = String(input.name || "").trim();
  const email = String(input.email || "").trim();
  const phone = String(input.mobilenumber || input.phone || "").replace(/\D/g, "");

  if (!name || !email || !phone) {
    throw new Error("Please fill name, email and mobile number.");
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    throw new Error("Please enter a valid email.");
  }
  if (phone.length < 10) {
    throw new Error("Please enter a valid mobile number.");
  }

  return { name, email, phone };
}
