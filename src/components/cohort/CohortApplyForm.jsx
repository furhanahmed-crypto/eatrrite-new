"use client";

import { useState } from "react";
import Script from "next/script";
import { siteConfig } from "@/config/site";
import { Button } from "@/shared/ui/button";
import { CohortApplyFields } from "@/components/cohort/CohortApplyFields";
import { payForCohort } from "@/components/cohort/cohortCheckout";

export function CohortApplyForm() {
  const [form, setForm] = useState({ name: "", email: "", mobilenumber: "" });
  const [alert, setAlert] = useState("");
  const [loading, setLoading] = useState(false);
  const fee = siteConfig.cohortConsultationRupees.toLocaleString("en-IN");

  function updateField(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setAlert("");
    setLoading(true);
    try {
      await payForCohort({
        form,
        onVerified(_response, verified) {
          sessionStorage.setItem("er_cohort_application", JSON.stringify(verified));
          window.location.assign(verified.redirect || "/cohort/thank-you");
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
        <CohortApplyFields form={form} updateField={updateField} />
        <p className="flex items-center justify-between text-sm font-semibold text-brand">
          <span>Consultation fee</span>
          <span>₹{fee}</span>
        </p>
        <Button
          type="submit"
          disabled={loading}
          className="h-[54px] w-full rounded-xl bg-gold text-base font-semibold text-ink hover:bg-[#f0c96a]"
        >
          {loading ? "Confirming…" : `Pay ₹${fee} consultation fee`}
        </Button>
      </form>
    </>
  );
}
