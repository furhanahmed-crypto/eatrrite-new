"use client";

import { useState } from "react";
import Script from "next/script";
import { siteConfig } from "@/config/site";
import { publicHoursNote, scheduleConfig } from "@/config/schedule";
import { Button } from "@/shared/ui/button";
import { SlotPickerModal } from "@/components/appointment/SlotPickerModal";
import { AppointmentFields } from "@/components/appointment/AppointmentFields";
import { createOrderAndPay } from "@/components/appointment/checkout";

export function AppointmentForm() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    mobilenumber: "",
    programname: "",
  });
  const [slot, setSlot] = useState(null);
  const [openSlots, setOpenSlots] = useState(false);
  const [alert, setAlert] = useState("");
  const [loading, setLoading] = useState(false);

  function updateField(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setAlert("");
    if (!form.programname) {
      setAlert("Please select a service to continue.");
      return;
    }
    if (!slot?.date || !slot?.time) {
      setOpenSlots(true);
      setAlert("Select an available date and time to continue.");
      return;
    }

    setLoading(true);
    try {
      await createOrderAndPay({
        form,
        slot,
        onVerified(response, verified) {
          sessionStorage.setItem(
            "er_verified_booking",
            JSON.stringify({ payment: response, verified })
          );
          window.location.assign(verified.redirect || "/appointment/thank-you");
        },
        onError(error) {
          console.error("[appointment] verify", error);
          setLoading(false);
          setAlert("A technical issue occurred. Please retry.");
        },
        onDismiss() {
          setLoading(false);
          setAlert("Payment was cancelled. Your slot stays held for a few minutes.");
        },
      });
    } catch (error) {
      console.error("[appointment] submit", error);
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
        <AppointmentFields
          form={form}
          updateField={updateField}
          slot={slot}
          onOpenSlots={() => setOpenSlots(true)}
        />
        <p className="m-0 text-[13px] leading-snug text-body">
          Confirming a slot holds it for {scheduleConfig.holdMinutes} minutes.
          A ₹{siteConfig.amountRupees} fee completes the booking. Hours (IST):{" "}
          {publicHoursNote()}.
        </p>
        <Button
          type="submit"
          disabled={loading}
          className="h-[54px] w-full rounded-xl bg-gold text-base font-semibold text-ink hover:bg-[#f0c96a]"
        >
          {loading ? "Processing…" : `Pay ₹${siteConfig.amountRupees} and book`}
        </Button>
      </form>
      <SlotPickerModal
        open={openSlots}
        onOpenChange={setOpenSlots}
        selected={slot}
        onSelect={setSlot}
      />
    </>
  );
}
