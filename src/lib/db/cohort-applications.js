import { prisma } from "@/lib/db";

function toPublic(row) {
  return {
    id: row.id,
    name: row.name,
    email: row.email,
    phone: row.phone,
    amount_rupees: row.amountRupees,
    cohort_month: row.cohortMonth,
    payment_id: row.paymentId || "",
    order_id: row.orderId || "",
    status: row.status,
    created_at: row.createdAt instanceof Date
      ? row.createdAt.toISOString()
      : String(row.createdAt || ""),
  };
}

export async function listCohortApplications() {
  const rows = await prisma.cohortApplication.findMany({
    orderBy: { createdAt: "desc" },
  });
  return rows.map(toPublic);
}

export async function deleteCohortApplication(id) {
  const row = await prisma.cohortApplication.delete({ where: { id } });
  return toPublic(row);
}

export async function findCohortApplicationByPaymentId(paymentId) {
  if (!paymentId) return null;
  const row = await prisma.cohortApplication.findFirst({ where: { paymentId } });
  return row ? toPublic(row) : null;
}

export async function countPaidCohortApplications(cohortMonth) {
  return prisma.cohortApplication.count({
    where: { cohortMonth, status: "paid" },
  });
}

export async function createCohortApplication(data) {
  const row = await prisma.cohortApplication.create({
    data: {
      name: data.name || "",
      email: data.email || "",
      phone: data.phone || "",
      amountRupees: Number(data.amountRupees) || 0,
      cohortMonth: data.cohortMonth || "",
      paymentId: data.paymentId || "",
      orderId: data.orderId || "",
      status: data.status || "paid",
    },
  });
  return toPublic(row);
}
