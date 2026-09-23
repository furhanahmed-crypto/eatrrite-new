import { createHmac } from "crypto";
import { getServerEnv } from "@/config/env";
import { siteConfig } from "@/config/site";

export async function createRazorpayOrder(notes, amountRupees = siteConfig.amountRupees) {
  const env = getServerEnv();
  const amount = Number(amountRupees) * 100;
  const auth = Buffer.from(
    `${env.razorpayKeyId}:${env.razorpayKeySecret}`
  ).toString("base64");

  const response = await fetch("https://api.razorpay.com/v1/orders", {
    method: "POST",
    headers: {
      Authorization: `Basic ${auth}`,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      amount,
      currency: siteConfig.currency,
      receipt: `er_${Date.now()}`,
      notes,
    }),
  });

  const data = await response.json();
  if (!response.ok) {
    throw new Error(data?.error?.description || "Razorpay order failed.");
  }

  return data;
}

export function verifyRazorpaySignature(orderId, paymentId, signature) {
  const env = getServerEnv();
  const payload = `${orderId}|${paymentId}`;
  const expected = createHmac("sha256", env.razorpayKeySecret)
    .update(payload)
    .digest("hex");
  return expected === signature;
}
