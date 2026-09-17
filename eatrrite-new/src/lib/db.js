import { PrismaClient } from "@prisma/client";

function cleanUrl(value) {
  const raw = String(value || "")
    .trim()
    .replace(/^["']|["']$/g, "");
  if (!raw) return raw;
  try {
    const url = new URL(raw);
    url.searchParams.delete("channel_binding");
    return url.toString();
  } catch {
    return raw;
  }
}

const globalForPrisma = globalThis;
const databaseUrl = cleanUrl(process.env.DATABASE_URL);

if (!globalForPrisma.prisma || globalForPrisma.prismaUrl !== databaseUrl) {
  if (globalForPrisma.prisma) {
    void globalForPrisma.prisma.$disconnect().catch(() => {});
  }
  globalForPrisma.prismaUrl = databaseUrl;
  globalForPrisma.prisma = new PrismaClient({
    datasources: { db: { url: databaseUrl } },
    log: process.env.NODE_ENV === "development" ? ["error", "warn"] : ["error"],
  });
}

export const prisma = globalForPrisma.prisma;
