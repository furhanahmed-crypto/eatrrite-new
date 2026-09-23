import { siteConfig } from "@/config/site";

export async function payForSnackbar({ form, onVerified, onError, onDismiss }) {
  const orderRes = await fetch("/api/snackbar/create-order", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(form),
  });
  const order = await orderRes.json();
  if (!order.ok) throw new Error(order.error || "Could not create order");

  const details = {
    name: form.name,
    email: form.email,
    phone: form.mobilenumber,
    address: form.address,
    quantity: Number(form.quantity),
  };

  const razorpay = new window.Razorpay({
    key: order.key_id,
    amount: order.amount,
    currency: order.currency,
    name: "Eat Rrite",
    description: `Snackbar × ${details.quantity}`,
    order_id: order.order_id,
    prefill: {
      name: form.name,
      email: form.email,
      contact: form.mobilenumber,
    },
    handler: async function handlePayment(response) {
      try {
        const verifyRes = await fetch("/api/snackbar/verify-payment", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ ...response, order: details }),
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

export function snackbarTotal(quantity) {
  return siteConfig.snackbarAmountRupees * Number(quantity || 1);
}
