import { prisma } from "@/lib/db";

export async function listDisabledSlots() {
  const rows = await prisma.disabledSlot.findMany({
    orderBy: [{ date: "asc" }, { time: "asc" }],
  });
  return rows.map((row) => ({ date: row.date, time: row.time }));
}

export async function setDisabledSlot(date, time, hidden) {
  if (hidden) {
    await prisma.disabledSlot.upsert({
      where: { date_time: { date, time } },
      create: { date, time },
      update: {},
    });
  } else {
    await prisma.disabledSlot.deleteMany({ where: { date, time } });
  }
  return { date, time, hidden: Boolean(hidden) };
}
