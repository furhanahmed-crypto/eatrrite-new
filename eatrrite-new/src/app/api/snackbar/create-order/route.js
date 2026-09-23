import { createRazorpayOrder } from "@/lib/razorpay";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { getServerEnv } from "@/config/env";
import { siteConfig } from "@/config/site";

export async function POST(request) {
  try {
    const body = await request.json();
    const name = String(body.name || "").trim();
    const email = String(body.email || "").trim();
    const phone = String(body.mobilenumber || body.phone || "").trim();
    const address = String(body.address || "").trim();
    const quantity = Number(body.quantity || 0);

    if (!name || !email || !phone || !address) {
      return jsonFail(new Error("Please fill name, email, mobile and address."), 400);
    }
    if (
      quantity < siteConfig.snackbarMinQty ||
      quantity > siteConfig.snackbarMaxQty
    ) {
      return jsonFail(new Error("Please choose a valid quantity."), 400);
    }

    const amountRupees = siteConfig.snackbarAmountRupees * quantity;
    const order = await createRazorpayOrder(
      { name, email, phone, address, quantity: String(quantity), product: "Snackbar" },
      amountRupees
    );

    return jsonOk({
      order_id: order.id,
      amount: order.amount,
      currency: order.currency,
      key_id: getServerEnv().razorpayKeyId,
      quantity,
      unit_rupees: siteConfig.snackbarAmountRupees,
    });
  } catch (error) {
    return jsonFail(error, 500);
  }
}
