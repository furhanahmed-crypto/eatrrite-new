(() => {
  const form = document.querySelector("[data-er-snackbar-form]");
  if (!form) return;

  const api = form.dataset.api;
  const csrf = form.dataset.csrf;
  const unit = Number(form.dataset.unitRupees || 3999);
  const minQty = Number(form.dataset.minQty || 1);
  const maxQty = Number(form.dataset.maxQty || 20);
  const alertEl = form.querySelector("[data-er-alert]");
  const submit = form.querySelector("[data-er-submit]");
  const qty = form.querySelector("[name=quantity]");
  const totalEl = form.querySelector("[data-er-total]");
  const breakdownEl = form.querySelector("[data-er-breakdown]");

  function showAlert(message) {
    if (!alertEl) return;
    alertEl.hidden = !message;
    alertEl.textContent = message || "";
  }

  function quantity() {
    return Math.min(maxQty, Math.max(minQty, Number(qty?.value || 1)));
  }

  function rupees() {
    return unit * quantity();
  }

  function refreshTotal() {
    const count = quantity();
    if (qty && Number(qty.value) !== count) qty.value = String(count);
    const label = `₹${rupees().toLocaleString("en-IN")}`;
    if (breakdownEl) {
      breakdownEl.textContent = `₹${unit.toLocaleString("en-IN")} × ${count}`;
    }
    if (totalEl) totalEl.textContent = label;
    if (submit) submit.textContent = `Pay ${label}`;
  }

  qty?.addEventListener("input", refreshTotal);
  form.querySelectorAll("[data-er-qty]").forEach((button) => {
    button.addEventListener("click", () => {
      if (!qty) return;
      qty.value = String(quantity() + Number(button.dataset.erQty || 0));
      refreshTotal();
    });
  });
  refreshTotal();

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    showAlert("");
    const payload = Object.fromEntries(new FormData(form).entries());
    payload.quantity = quantity();
    payload.phone = payload.mobilenumber;
    submit.disabled = true;
    submit.textContent = "Confirming…";

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
            refreshTotal();
            showAlert("Payment was cancelled. You can try again.");
          },
        },
      });
      razorpay.open();
    } catch (error) {
      submit.disabled = false;
      refreshTotal();
      showAlert(error.message || "A technical issue occurred. Please retry.");
    }
  });
})();
