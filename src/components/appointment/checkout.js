"use client";

import { siteConfig } from "@/config/site";

export async function createOrderAndPay({
  form,
  slot,
  onVerified,
  onError,
  onDismiss,
}) {
  const orderRes = await fetch("/api/appointment/create-order", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      ...form,
      date: slot.date,
      time: slot.time,
      holdId: slot.holdId || "",
    }),
  });
  const order = await orderRes.json();
  if (!order.ok) throw new Error(order.error || "Could not create order");

  const booking = {
    name: form.name,
    email: form.email,
    phone: form.mobilenumber,
    service: form.programname,
    date: slot.date,
    time: slot.time,
  };

  const razorpay = new window.Razorpay({
    key: order.key_id,
    amount: order.amount,
    currency: order.currency,
    name: "Eat Rrite",
    description: `Appointment confirmation · ₹${siteConfig.amountRupees}`,
    order_id: order.order_id,
    prefill: {
      name: form.name,
      email: form.email,
      contact: form.mobilenumber,
    },
    handler: async function handlePayment(response) {
      try {
        const verifyRes = await fetch("/api/appointment/verify-payment", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ ...response, booking }),
        });
        const verified = await verifyRes.json();
        if (!verified.ok) throw new Error(verified.error || "Verify failed");
        onVerified(response, verified);
      } catch (error) {
        onError(error);
      }
    },
    modal: { ondismiss: onDismiss },
  });

  razorpay.open();
}
