import { verifyRazorpaySignature } from "@/lib/razorpay";
import { createBooking, findBookingByPaymentId } from "@/lib/db/bookings";
import { clearHoldByOrderOrSlot } from "@/lib/db/holds";
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

    const existing = await findBookingByPaymentId(paymentId);
    if (existing) {
      return jsonOk({
        ...existing,
        meet_link_ready: Boolean(existing.meet_link),
        redirect: "/appointment/thank-you",
      });
    }

    const date = String(booking.date || "");
    const time = String(booking.time || "");

    const record = await createBooking({
      name: String(booking.name || ""),
      email: String(booking.email || ""),
      phone: String(booking.phone || ""),
      service: String(booking.service || ""),
      date,
      time,
      paymentId,
      orderId,
      meetLink: "",
      status: "verified",
    });

    await clearHoldByOrderOrSlot({ orderId, date, time });

    return jsonOk({
      ...record,
      meet_link_ready: false,
      redirect: "/appointment/thank-you",
    });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
