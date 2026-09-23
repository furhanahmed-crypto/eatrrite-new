import { prisma } from "@/lib/db";

function toPublic(row) {
  return {
    id: row.id,
    name: row.name,
    email: row.email,
    phone: row.phone,
    address: row.address,
    quantity: row.quantity,
    amount_rupees: row.amountRupees,
    payment_id: row.paymentId || "",
    order_id: row.orderId || "",
    status: row.status,
  };
}

export async function findSnackbarOrderByPaymentId(paymentId) {
  if (!paymentId) return null;
  const row = await prisma.snackbarOrder.findFirst({ where: { paymentId } });
  return row ? toPublic(row) : null;
}

export async function createSnackbarOrder(data) {
  const row = await prisma.snackbarOrder.create({
    data: {
      name: data.name || "",
      email: data.email || "",
      phone: data.phone || "",
      address: data.address || "",
      quantity: Number(data.quantity) || 1,
      amountRupees: Number(data.amountRupees) || 0,
      paymentId: data.paymentId || "",
      orderId: data.orderId || "",
      status: data.status || "paid",
    },
  });
  return toPublic(row);
}
