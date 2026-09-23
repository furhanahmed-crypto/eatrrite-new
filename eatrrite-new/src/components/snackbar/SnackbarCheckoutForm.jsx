"use client";

import { useState } from "react";
import Script from "next/script";
import { siteConfig } from "@/config/site";
import { Button } from "@/shared/ui/button";
import { SnackbarCheckoutFields } from "@/components/snackbar/SnackbarCheckoutFields";
import { payForSnackbar, snackbarTotal } from "@/components/snackbar/snackbarCheckout";

export function SnackbarCheckoutForm() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    mobilenumber: "",
    address: "",
    quantity: "1",
  });
  const [alert, setAlert] = useState("");
  const [loading, setLoading] = useState(false);
  const total = snackbarTotal(form.quantity);

  function updateField(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setAlert("");
    setLoading(true);
    try {
      await payForSnackbar({
        form,
        onVerified(_response, verified) {
          sessionStorage.setItem("er_snackbar_order", JSON.stringify(verified));
          window.location.assign(verified.redirect || "/snackbar/thank-you");
        },
        onError() {
          setLoading(false);
          setAlert("A technical issue occurred. Please retry.");
        },
        onDismiss() {
          setLoading(false);
          setAlert("Payment was cancelled. You can try again.");
        },
      });
    } catch (error) {
      setLoading(false);
      setAlert(error.message || "A technical issue occurred. Please retry.");
    }
  }

  return (
    <>
      <Script src="https://checkout.razorpay.com/v1/checkout.js" strategy="lazyOnload" />
      <form onSubmit={handleSubmit} className="grid gap-3.5 text-left">
        {alert ? (
          <p className="rounded-xl bg-[#fdecee] px-3.5 py-3 text-sm text-[#842029]">
            {alert}
          </p>
        ) : null}
        <SnackbarCheckoutFields form={form} updateField={updateField} />
        <div className="flex items-end justify-between gap-4 border-t border-gold/30 pt-4">
          <p className="text-sm text-soft">
            ₹{siteConfig.snackbarAmountRupees.toLocaleString("en-IN")} × {form.quantity || 1}
          </p>
          <p className="text-right">
            <span className="block text-[11px] font-semibold tracking-[0.14em] text-soft uppercase">
              Total
            </span>
            <span className="font-heading text-[1.65rem] leading-none text-brand">
              ₹{total.toLocaleString("en-IN")}
            </span>
          </p>
        </div>
        <Button
          type="submit"
          disabled={loading}
          className="h-12 w-full rounded-lg border border-gold bg-gold text-[15px] font-semibold text-ink transition hover:bg-[#f0c96a]"
        >
          {loading ? "Confirming…" : `Pay ₹${total.toLocaleString("en-IN")}`}
        </Button>
      </form>
    </>
  );
}
