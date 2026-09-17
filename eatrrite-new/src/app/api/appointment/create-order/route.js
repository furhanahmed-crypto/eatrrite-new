import { createRazorpayOrder } from "@/lib/razorpay";
import { attachOrderHold } from "@/lib/holds";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { getServerEnv } from "@/config/env";
import { randomUUID } from "crypto";

export async function POST(request) {
  try {
    const body = await request.json();
    const name = String(body.name || "").trim();
    const email = String(body.email || "").trim();
    const phone = String(body.mobilenumber || "").trim();
    const service = String(body.programname || "").trim();
    const date = String(body.date || "").trim();
    const time = String(body.time || "").trim();
    const holdId = String(body.holdId || "").trim() || randomUUID();

    if (!name || !email || !phone || !service || !date || !time) {
      return jsonFail(new Error("Please fill all fields."), 400);
    }

    const order = await createRazorpayOrder({
      name,
      email,
      phone,
      service,
      date,
      time,
    });

    await attachOrderHold(date, time, holdId, order.id);

    const env = getServerEnv();
    return jsonOk({
      order_id: order.id,
      amount: order.amount,
      currency: order.currency,
      key_id: env.razorpayKeyId,
      holdId,
    });
  } catch (error) {
    const status = /no longer available|slot/i.test(error.message || "")
      ? 409
      : 500;
    return jsonFail(error, status);
  }
}
