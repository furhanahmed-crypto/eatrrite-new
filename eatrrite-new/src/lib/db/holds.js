import { prisma } from "@/lib/db";
import { scheduleConfig } from "@/config/schedule";

function toPublic(row) {
  return {
    holdId: row.holdId,
    date: row.date,
    time: row.time,
    orderId: row.orderId || "",
    expiresAt: row.expiresAt.getTime(),
  };
}

/** Remove expired holds, return active ones (optionally skip one holdId). */
export async function listActiveHolds(ignoreHoldId = "") {
  const now = new Date();
  await prisma.hold.deleteMany({ where: { expiresAt: { lte: now } } });

  const rows = await prisma.hold.findMany({
    where: {
      expiresAt: { gt: now },
      ...(ignoreHoldId ? { holdId: { not: ignoreHoldId } } : {}),
    },
  });

  return rows.map(toPublic);
}

export async function upsertHold({ holdId, date, time, orderId = "" }) {
  const expiresAt = new Date(
    Date.now() + scheduleConfig.holdMinutes * 60 * 1000
  );

  const row = await prisma.hold.upsert({
    where: { holdId },
    create: { holdId, date, time, orderId, expiresAt },
    update: { date, time, orderId, expiresAt },
  });

  return toPublic(row);
}

export async function clearHoldByOrderOrSlot({ orderId, date, time }) {
  await prisma.hold.deleteMany({
    where: {
      OR: [
        orderId ? { orderId } : undefined,
        date && time ? { date, time } : undefined,
      ].filter(Boolean),
    },
  });
}
