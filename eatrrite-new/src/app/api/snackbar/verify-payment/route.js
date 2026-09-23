import { verifyRazorpaySignature } from "@/lib/razorpay";
import {
  createSnackbarOrder,
  findSnackbarOrderByPaymentId,
} from "@/lib/db/snackbar-orders";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { siteConfig } from "@/config/site";

export async function POST(request) {
  try {
    const body = await request.json();
    const orderId = String(body.razorpay_order_id || "");
    const paymentId = String(body.razorpay_payment_id || "");
    const signature = String(body.razorpay_signature || "");
    const details = body.order || {};

    if (!verifyRazorpaySignature(orderId, paymentId, signature)) {
      return jsonFail(new Error("Payment verification failed."), 400);
    }

    const existing = await findSnackbarOrderByPaymentId(paymentId);
    if (existing) {
      return jsonOk({ ...existing, redirect: "/snackbar/thank-you" });
    }

    const quantity = Number(details.quantity || 0);
    const record = await createSnackbarOrder({
      name: String(details.name || ""),
      email: String(details.email || ""),
      phone: String(details.phone || details.mobilenumber || ""),
      address: String(details.address || ""),
      quantity,
      amountRupees: siteConfig.snackbarAmountRupees * quantity,
      paymentId,
      orderId,
      status: "paid",
    });

    return jsonOk({ ...record, redirect: "/snackbar/thank-you" });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
