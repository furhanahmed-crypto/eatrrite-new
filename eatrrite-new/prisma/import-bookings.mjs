/**
 * One-time helper: import bookings from a CSV file.
 *
 * Usage:
 *   1. Save CSV → prisma/import/bookings.csv
 *   2. bun run db:import-bookings
 *
 * Expected columns (header row):
 * Name,Service,Phone Number,Appointment Date,Appointment Time,Google Meet Link,Appointment Booked At
 */
import { readFileSync } from "fs";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();
const csvPath = new URL("./import/bookings.csv", import.meta.url);

function parseCsv(text) {
  const lines = text.trim().split(/\r?\n/);
  if (lines.length < 2) return [];
  const rows = [];
  for (let i = 1; i < lines.length; i += 1) {
    const cols = lines[i].split(",").map((c) => c.trim().replace(/^"|"$/g, ""));
    if (cols.length < 5) continue;
    rows.push({
      name: cols[0] || "",
      service: cols[1] || "",
      phone: cols[2] || "",
      date: cols[3] || "",
      time: normalizeTime(cols[4] || ""),
      meetLink: cols[5] || "",
      bookedAt: cols[6] ? new Date(cols[6]) : new Date(),
    });
  }
  return rows;
}

function normalizeTime(value) {
  const m24 = value.match(/^([01]?\d|2[0-3]):([0-5]\d)/);
  if (m24) {
    return `${String(m24[1]).padStart(2, "0")}:${m24[2]}`;
  }
  return value;
}

async function main() {
  const text = readFileSync(csvPath, "utf8");
  const rows = parseCsv(text);
  let created = 0;
  let skipped = 0;

  for (const row of rows) {
    if (!row.date || !row.time) {
      skipped += 1;
      continue;
    }
    try {
      await prisma.booking.upsert({
        where: { date_time: { date: row.date, time: row.time } },
        create: {
          name: row.name,
          service: row.service,
          phone: row.phone,
          date: row.date,
          time: row.time,
          meetLink: row.meetLink,
          status: "completed",
          bookedAt: Number.isNaN(row.bookedAt.getTime())
            ? new Date()
            : row.bookedAt,
        },
        update: {
          name: row.name,
          service: row.service,
          phone: row.phone,
          meetLink: row.meetLink || undefined,
        },
      });
      created += 1;
    } catch (error) {
      console.error("Skip row", row, error.message);
      skipped += 1;
    }
  }

  console.log(`Imported ${created} bookings (${skipped} skipped).`);
}

main()
  .catch((error) => {
    console.error(error);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
