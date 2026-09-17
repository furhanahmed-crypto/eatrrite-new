import { promises as fs } from "fs";
import path from "path";

const storageDir = path.join(process.cwd(), "storage");

async function ensureStorage() {
  await fs.mkdir(storageDir, { recursive: true });
}

async function readJson(file, fallback) {
  await ensureStorage();
  const full = path.join(storageDir, file);
  try {
    const raw = await fs.readFile(full, "utf8");
    return JSON.parse(raw || "null") ?? fallback;
  } catch {
    return fallback;
  }
}

async function writeJson(file, data) {
  await ensureStorage();
  const full = path.join(storageDir, file);
  await fs.writeFile(full, JSON.stringify(data, null, 2));
}

export async function readBookings() {
  return readJson("bookings.json", []);
}

export async function saveBookings(rows) {
  return writeJson("bookings.json", rows);
}

export async function readHolds() {
  return readJson("holds.json", []);
}

export async function saveHolds(rows) {
  return writeJson("holds.json", rows);
}
