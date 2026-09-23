"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { siteConfig } from "@/config/site";

function readApplication() {
  try {
    const raw = sessionStorage.getItem("er_cohort_application");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export default function CohortThankYouPage() {
  const [application, setApplication] = useState(null);
  const [ready, setReady] = useState(false);
  const monthly = siteConfig.cohortMonthlyRupees.toLocaleString("en-IN");

  useEffect(() => {
    setTimeout(() => {
      setApplication(readApplication());
      setReady(true);
    }, 0);
  }, []);

  return (
    <SiteShell current="cohort">
      <PageBanner title="Thank you" crumb="Cohort" />
      <section className="bg-cream py-12 md:py-24">
        <div className="container-er max-w-2xl">
          <div className="rounded-[20px] border border-border-soft bg-surface px-4 py-10 text-center shadow-er min-[400px]:px-6">
            <div className="mx-auto mb-4 grid size-14 place-items-center rounded-full bg-brand text-2xl text-white">
              ✓
            </div>
            {!ready ? (
              <p className="text-soft">Loading your application…</p>
            ) : application?.name ? (
              <>
                <h1 className="font-heading text-[clamp(1.5rem,6vw,2rem)] text-brand">
                  Thank you, {application.name}
                </h1>
                <p className="mx-auto mt-3 max-w-md text-body">
                  Your ₹
                  {Number(application.amount_rupees || 0).toLocaleString("en-IN")}{" "}
                  consultation is confirmed. The first month is complimentary.
                  From next month you may continue at ₹{monthly} or step away.
                  We will write to you within 48 hours.
                </p>
              </>
            ) : (
              <>
                <h1 className="font-heading text-2xl text-brand">Thank you</h1>
                <p className="mt-3 text-body">
                  No recent cohort application was found on this device.
                </p>
              </>
            )}
            <p className="mt-6 text-sm text-soft">
              Questions? Write to{" "}
              <a href={siteConfig.emailHref} className="text-brand underline">
                {siteConfig.email}
              </a>
              .
            </p>
            <Link
              href="/"
              className="mt-6 inline-flex h-11 items-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white"
            >
              Back home
            </Link>
          </div>
        </div>
      </section>
    </SiteShell>
  );
}
