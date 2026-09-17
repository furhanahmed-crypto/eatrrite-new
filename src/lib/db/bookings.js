import { prisma } from "@/lib/db";

function toPublic(row) {
  return {
    id: row.id,
    name: row.name,
    email: row.email,
    phone: row.phone,
    service: row.service,
    date: row.date,
    time: row.time,
    meet_link: row.meetLink || "",
    payment_id: row.paymentId || "",
    order_id: row.orderId || "",
    status: row.status,
    booked_at: row.bookedAt
      ? new Date(row.bookedAt).toISOString().slice(0, 19).replace("T", " ")
      : "",
  };
}

export async function listBookings() {
  const rows = await prisma.booking.findMany({
    orderBy: [{ date: "asc" }, { time: "asc" }],
  });
  return rows.map(toPublic);
}

export async function findBookingByPaymentId(paymentId) {
  if (!paymentId) return null;
  const row = await prisma.booking.findFirst({ where: { paymentId } });
  return row ? toPublic(row) : null;
}

export async function findBookingMatch({ date, time, phone, name }) {
  if (!date || !time) return null;

  const row = await prisma.booking.findFirst({
    where: {
      date,
      time,
      ...(phone ? { phone } : name ? { name } : {}),
    },
  });
  return row ? toPublic(row) : null;
}

export async function createBooking(data) {
  const row = await prisma.booking.create({
    data: {
      name: data.name || "",
      email: data.email || "",
      phone: data.phone || "",
      service: data.service || "",
      date: data.date,
      time: data.time,
      meetLink: data.meetLink || "",
      paymentId: data.paymentId || "",
      orderId: data.orderId || "",
      status: data.status || "completed",
      bookedAt: data.bookedAt ? new Date(data.bookedAt) : new Date(),
    },
  });
  return toPublic(row);
}

export async function updateBookingMeet(id, meetLink) {
  const row = await prisma.booking.update({
    where: { id },
    data: { meetLink, status: "completed" },
  });
  return toPublic(row);
}

export async function deleteBooking({ date, time, phone, name }) {
  const existing = await prisma.booking.findFirst({
    where: {
      date,
      time,
      AND: [
        phone ? { phone } : {},
        name ? { name } : {},
      ],
    },
  });

  if (!existing) {
    throw new Error("Booking not found.");
  }

  await prisma.booking.delete({ where: { id: existing.id } });
  return toPublic(existing);
}
