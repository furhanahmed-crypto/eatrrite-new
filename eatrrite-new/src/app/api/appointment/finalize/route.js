import { bookAppointment, listAppointments } from "@/lib/apps-script";
import { blockMinutes, scheduleConfig } from "@/config/schedule";
import { readBookings, saveBookings } from "@/lib/storage";
import { jsonFail, jsonOk } from "@/lib/api-response";

function toIso(date, time, addMinutes = 0) {
  const [h, m] = time.split(":").map(Number);
  const value = new Date(`${date}T00:00:00+05:30`);
  value.setMinutes(value.getMinutes() + h * 60 + m + addMinutes);
  return value.toISOString();
}

async function findSheetBooking(record) {
  const booked = await listAppointments().catch(() => []);
  return booked.find(
    (row) =>
      row.date === record.date &&
      row.time === record.time &&
      row.meet_link &&
      (row.phone === record.phone || row.name === record.name)
  );
}

export async function POST(request) {
  try {
    const body = await request.json();
    const paymentId = String(body.razorpay_payment_id || body.payment_id || "");
    const bookings = await readBookings();
    const index = bookings.findIndex((row) => row.payment_id === paymentId);

    if (index < 0) {
      return jsonFail(new Error("Booking record not found."), 400);
    }

    const record = bookings[index];
    if (record.status === "completed" && record.meet_link) {
      return jsonOk({ status: "completed", ...record });
    }

    if (record.status === "finalizing") {
      return jsonOk({ status: "processing" });
    }

    bookings[index] = { ...record, status: "finalizing" };
    await saveBookings(bookings);

    try {
      const result = await bookAppointment({
        name: record.name,
        service: record.service,
        phone: record.phone,
        date: record.date,
        time: record.time,
        payment_id: record.payment_id,
        start_iso: toIso(record.date, record.time),
        end_iso: toIso(
          record.date,
          record.time,
          scheduleConfig.customerMeetingMinutes
        ),
        consultant_block_minutes: blockMinutes(),
      });

      const completed = {
        ...record,
        status: "completed",
        meet_link: result.meet_link,
        booked_at: result.booked_at || "",
        completed_at: Date.now(),
      };
      bookings[index] = completed;
      await saveBookings(bookings);
      return jsonOk({ status: "completed", ...completed });
    } catch (error) {
      if (error.code === "slot_taken") {
        const existing = await findSheetBooking(record);
        if (existing?.meet_link) {
          const completed = {
            ...record,
            status: "completed",
            meet_link: existing.meet_link,
            booked_at: existing.booked_at || "",
            completed_at: Date.now(),
          };
          bookings[index] = completed;
          await saveBookings(bookings);
          return jsonOk({ status: "completed", ...completed });
        }
      }

      bookings[index] = { ...record, status: "verified" };
      await saveBookings(bookings);
      throw error;
    }
  } catch (error) {
    return jsonFail(error, 500);
  }
}
