import { scheduleConfig } from "@/config/schedule";
import { createRazorpayOrder } from "@/lib/razorpay";
import { buildAvailability } from "@/lib/slots";
import { readHolds, saveHolds } from "@/lib/storage";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { getServerEnv } from "@/config/env";

export async function POST(request) {
  try {
    const body = await request.json();
    const name = String(body.name || "").trim();
    const email = String(body.email || "").trim();
    const phone = String(body.mobilenumber || "").trim();
    const service = String(body.programname || "").trim();
    const date = String(body.date || "").trim();
    const time = String(body.time || "").trim();

    if (!name || !email || !phone || !service || !date || !time) {
      return jsonFail(new Error("Please fill all fields."), 400);
    }

    const availability = await buildAvailability();
    if (!(availability.days[date] || []).includes(time)) {
      return jsonFail(new Error("That slot is no longer available."), 409);
    }

    const order = await createRazorpayOrder({
      name,
      email,
      phone,
      service,
      date,
      time,
    });

    const holds = await readHolds();
    const expiresAt = Date.now() + scheduleConfig.holdMinutes * 60 * 1000;
    holds.push({ date, time, orderId: order.id, expiresAt });
    await saveHolds(holds);

    const env = getServerEnv();
    return jsonOk({
      order_id: order.id,
      amount: order.amount,
      currency: order.currency,
      key_id: env.razorpayKeyId,
    });
  } catch (error) {
    const status = /slot/i.test(error.message || "") ? 409 : 500;
    return jsonFail(error, status);
  }
}
