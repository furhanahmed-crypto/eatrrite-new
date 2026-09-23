(() => {
  const form = document.querySelector("[data-er-snackbar-form]");
  if (!form) return;

  const api = form.dataset.api;
  const csrf = form.dataset.csrf;
  const unit = Number(form.dataset.unitRupees || 3999);
  const alertEl = form.querySelector("[data-er-alert]");
  const submit = form.querySelector("[data-er-submit]");
  const qty = form.querySelector("[name=quantity]");
  const totalEl = form.querySelector("[data-er-total]");

  function showAlert(message) {
    if (!alertEl) return;
    alertEl.hidden = !message;
    alertEl.textContent = message || "";
  }

  function rupees() {
    const quantity = Math.max(1, Number(qty?.value || 1));
    return unit * quantity;
  }

  function refreshTotal() {
    if (totalEl) totalEl.textContent = `₹${rupees().toLocaleString("en-IN")}`;
    if (submit) submit.textContent = `Pay ₹${rupees().toLocaleString("en-IN")}`;
  }

  qty?.addEventListener("input", refreshTotal);
  refreshTotal();

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    showAlert("");
    const payload = Object.fromEntries(new FormData(form).entries());
    payload.quantity = Number(payload.quantity || 1);
    payload.phone = payload.mobilenumber;
    submit.disabled = true;

    try {
      const orderRes = await fetch(`${api}/create-order.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf },
        body: JSON.stringify(payload),
      });
      const order = await orderRes.json();
      if (!order.ok) throw new Error(order.error || "Could not start payment.");

      const razorpay = new window.Razorpay({
        key: order.key_id,
        amount: order.amount,
        currency: order.currency,
        name: "Eat Rrite",
        description: `Snackbar × ${payload.quantity}`,
        order_id: order.order_id,
        prefill: { name: payload.name, email: payload.email, contact: payload.mobilenumber },
        handler: async (response) => {
          const verifyRes = await fetch(`${api}/verify-payment.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf },
            body: JSON.stringify({ ...response, order: payload }),
          });
          const verified = await verifyRes.json();
          if (!verified.ok) throw new Error(verified.error || "Payment check failed.");
          window.location.assign(verified.redirect || "thank-you.php");
        },
        modal: {
          ondismiss() {
            submit.disabled = false;
            showAlert("Payment was cancelled. You can try again.");
          },
        },
      });
      razorpay.open();
    } catch (error) {
      submit.disabled = false;
      showAlert(error.message || "A technical issue occurred. Please retry.");
    }
  });
})();
