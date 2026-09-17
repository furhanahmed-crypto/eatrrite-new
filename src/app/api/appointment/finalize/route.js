import { createMeetLink } from "@/lib/meet";
import { blockMinutes, scheduleConfig } from "@/config/schedule";
import {
  createBooking,
  findBookingByPaymentId,
  findBookingMatch,
  updateBookingMeet,
} from "@/lib/db/bookings";
import { clearHoldByOrderOrSlot } from "@/lib/db/holds";
import { jsonFail, jsonOk } from "@/lib/api-response";

function toIso(date, time, addMinutes = 0) {
  const [h, m] = time.split(":").map(Number);
  const value = new Date(`${date}T00:00:00+05:30`);
  value.setMinutes(value.getMinutes() + h * 60 + m + addMinutes);
  return value.toISOString();
}

export async function POST(request) {
  try {
    const body = await request.json();
    const paymentId = String(body.razorpay_payment_id || body.payment_id || "");

    if (!paymentId) {
      return jsonFail(new Error("Payment id is required."), 400);
    }

    const existing = await findBookingByPaymentId(paymentId);
    if (existing?.meet_link) {
      return jsonOk({ status: "completed", ...existing });
    }

    if (existing && existing.status === "finalizing") {
      return jsonOk({ status: "processing" });
    }

    // Prefer the verified booking row already stored at payment time.
    const record =
      existing ||
      (await findBookingMatch({
        date: String(body.date || ""),
        time: String(body.time || ""),
        phone: String(body.phone || ""),
        name: String(body.name || ""),
      }));

    if (!record) {
      return jsonFail(new Error("Booking record not found."), 400);
    }

    // Mark as finalizing by writing status via meet update path after Meet.
    try {
      const meet = await createMeetLink({
        name: record.name,
        service: record.service,
        phone: record.phone,
        paymentId: record.payment_id,
        startIso: toIso(record.date, record.time),
        endIso: toIso(
          record.date,
          record.time,
          scheduleConfig.customerMeetingMinutes
        ),
      });

      const completed = await updateBookingMeet(record.id, meet.meet_link);
      await clearHoldByOrderOrSlot({
        orderId: record.order_id,
        date: record.date,
        time: record.time,
      });

      return jsonOk({
        status: "completed",
        ...completed,
        consultant_block_minutes: blockMinutes(),
      });
    } catch (error) {
      // Recover if Meet already exists from a prior attempt.
      const again = await findBookingByPaymentId(paymentId);
      if (again?.meet_link) {
        return jsonOk({ status: "completed", ...again });
      }
      throw error;
    }
  } catch (error) {
    return jsonFail(error, 500);
  }
}
