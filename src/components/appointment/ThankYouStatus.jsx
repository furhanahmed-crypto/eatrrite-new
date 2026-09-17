"use client";

import Link from "next/link";
import { Loader2 } from "lucide-react";
import { siteConfig } from "@/config/site";

export function ThankYouStatus({
  booking,
  meetLink,
  meetPending,
  meetError,
}) {
  if (!booking) {
    return (
      <p className="text-soft">
        No recent booking found.{" "}
        <Link href="/appointment" className="underline">
          Book an appointment
        </Link>
        .
      </p>
    );
  }

  return (
    <div className="space-y-8">
      <div className="rounded-[20px] border border-border-soft bg-surface px-4 py-8 text-center shadow-er min-[400px]:px-6 min-[400px]:py-10">
        <div className="mx-auto mb-4 grid size-14 place-items-center rounded-full bg-brand text-2xl text-white">
          ✓
        </div>
        <h2 className="font-heading text-[clamp(1.35rem,6vw,1.75rem)] text-brand md:text-[28px]">
          Appointment confirmed
        </h2>
        <p className="mx-auto mt-3 max-w-lg text-body">
          Hi {booking.name}, your {booking.service} consultation is booked for{" "}
          {booking.display_date} at {booking.display_time}.
        </p>

        <div className="mx-auto mt-6 max-w-lg">
          {meetPending ? (
            <div className="inline-flex items-center gap-3 rounded-[10px] bg-mint px-4 py-3 text-left text-sm text-body">
              <Loader2 className="size-4 shrink-0 animate-spin text-brand" />
              <span>
                Generating Google Meet link… This may take a couple of minutes.
              </span>
            </div>
          ) : null}
          {meetLink ? (
            <a
              href={meetLink}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-block break-all rounded-[10px] bg-mint px-4 py-3 text-sm font-semibold text-brand"
            >
              {meetLink}
            </a>
          ) : null}
          {meetError ? (
            <p className="rounded-[10px] bg-orange-50 px-4 py-3 text-sm text-orange-800">
              {meetError}
            </p>
          ) : null}
        </div>

        <p className="mt-4 text-[13px] text-soft">
          {meetLink
            ? "Save the Google Meet link above. If you do not receive the confirmation email shortly, contact us."
            : "We will email your confirmation once your Meet link is ready."}
        </p>
      </div>

      <div className="space-y-4">
        <h2 className="font-heading text-[clamp(1.5rem,7vw,1.875rem)] text-ink">
          What happens next?
        </h2>
        <p className="text-body">
          Your payment has been received and your consultation slot is reserved
          with Eat Rrite. We are creating your Google Meet link and adding your
          appointment to our schedule.
        </p>
        <p className="text-body">
          You will receive a confirmation email with your appointment details
          and Meet link. For any questions, reach us at{" "}
          <a href={siteConfig.emailHref} className="text-brand underline">
            {siteConfig.email}
          </a>{" "}
          or call{" "}
          <a href={siteConfig.phoneHref} className="text-brand underline">
            {siteConfig.phone}
          </a>
          .
        </p>
        <Link
          href="/"
          className="inline-flex h-11 items-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white"
        >
          Back to Home
        </Link>
      </div>
    </div>
  );
}
