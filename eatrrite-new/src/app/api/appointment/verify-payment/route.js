import { verifyRazorpaySignature } from "@/lib/razorpay";
import { readBookings, saveBookings, readHolds, saveHolds } from "@/lib/storage";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function POST(request) {
  try {
    const body = await request.json();
    const orderId = String(body.razorpay_order_id || "");
    const paymentId = String(body.razorpay_payment_id || "");
    const signature = String(body.razorpay_signature || "");
    const booking = body.booking || {};

    if (!verifyRazorpaySignature(orderId, paymentId, signature)) {
      return jsonFail(new Error("Payment verification failed."), 400);
    }

    const bookings = await readBookings();
    const existing = bookings.find((row) => row.payment_id === paymentId);
    if (existing) {
      return jsonOk({
        payment_id: paymentId,
        order_id: orderId,
        meet_link_ready: Boolean(existing.meet_link),
        meet_link: existing.meet_link || "",
        ...existing,
        redirect: "/appointment/thank-you",
      });
    }

    const holds = await readHolds();
    await saveHolds(holds.filter((row) => row.orderId !== orderId));

    const record = {
      payment_id: paymentId,
      order_id: orderId,
      status: "verified",
      name: String(booking.name || ""),
      email: String(booking.email || ""),
      phone: String(booking.phone || ""),
      service: String(booking.service || ""),
      date: String(booking.date || ""),
      time: String(booking.time || ""),
      meet_link: "",
      verified_at: Date.now(),
    };

    bookings.push(record);
    await saveBookings(bookings);

    return jsonOk({
      ...record,
      meet_link_ready: false,
      redirect: "/appointment/thank-you",
    });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
