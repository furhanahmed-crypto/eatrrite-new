"use client";

import { useEffect, useState } from "react";
import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { ThankYouStatus } from "@/components/appointment/ThankYouStatus";
import {
  formatDayLabel,
  formatDisplayTime,
} from "@/components/appointment/slot-helpers";

const MEET_ERROR =
  "A technical issue occurred while preparing your Meet link. Your payment was received — please retry or contact us and we will follow up shortly.";

function readVerifiedBooking() {
  if (typeof window === "undefined") return null;
  const raw = sessionStorage.getItem("er_verified_booking");
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}

function toBookingView(verified) {
  if (!verified) return null;
  return {
    name: verified.name || "there",
    service: verified.service || "consultation",
    display_date: verified.date
      ? formatDayLabel(verified.date)
      : "your selected date",
    display_time: verified.time
      ? formatDisplayTime(verified.time)
      : "your selected time",
  };
}

async function pollFinalize(paymentId) {
  for (let attempt = 0; attempt < 60; attempt += 1) {
    const res = await fetch("/api/appointment/finalize", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ razorpay_payment_id: paymentId }),
    });
    const data = await res.json().catch(() => ({ ok: false }));
    if (!res.ok || !data.ok) {
      console.error("[appointment] finalize", data);
      throw new Error(data.error || "Finalize failed");
    }
    if (data.status === "completed" && data.meet_link) return data;
    await new Promise((resolve) => setTimeout(resolve, 3000));
  }
  throw new Error(MEET_ERROR);
}

export default function ThankYouPage() {
  const [payload] = useState(readVerifiedBooking);
  const verified = payload?.verified || null;
  const paymentId =
    payload?.payment?.razorpay_payment_id || verified?.payment_id || "";
  const readyLink =
    verified?.meet_link_ready && verified?.meet_link ? verified.meet_link : "";
  const booking = toBookingView(verified);

  const [meetLink, setMeetLink] = useState(readyLink);
  const [meetPending, setMeetPending] = useState(
    Boolean(verified && paymentId && !readyLink)
  );
  const [meetError, setMeetError] = useState(
    verified && !paymentId ? MEET_ERROR : ""
  );

  useEffect(() => {
    if (!verified || meetLink || !paymentId) return;

    let cancelled = false;
    pollFinalize(paymentId)
      .then((result) => {
        if (cancelled) return;
        setMeetLink(result.meet_link);
        setMeetPending(false);
      })
      .catch((err) => {
        console.error("[appointment] meet link", err);
        if (!cancelled) {
          setMeetPending(false);
          setMeetError(MEET_ERROR);
        }
      });

    return () => {
      cancelled = true;
    };
  }, [verified, paymentId, meetLink]);

  return (
    <SiteShell current="appointment">
      <PageBanner title="Appointment Confirmed" pill="Appointment" />
      <section className="bg-cream py-16 md:py-24">
        <div className="container-er max-w-3xl">
          <ThankYouStatus
            booking={booking}
            meetLink={meetLink}
            meetPending={meetPending}
            meetError={meetError}
          />
        </div>
      </section>
    </SiteShell>
  );
}
