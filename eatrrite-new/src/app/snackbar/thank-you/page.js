"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { siteConfig } from "@/config/site";

function readOrder() {
  try {
    const raw = sessionStorage.getItem("er_snackbar_order");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export default function SnackbarThankYouPage() {
  const [order, setOrder] = useState(null);
  const [ready, setReady] = useState(false);

  useEffect(() => {
    setTimeout(() => {
      setOrder(readOrder());
      setReady(true);
    }, 0);
  }, []);

  return (
    <SiteShell current="snackbar">
      <PageBanner title="Thank you" crumb="Snackbar" />
      <section className="bg-cream py-12 md:py-24">
        <div className="container-er max-w-2xl">
          <div className="rounded-[20px] border border-border-soft bg-surface px-4 py-10 text-center shadow-er min-[400px]:px-6">
            <div className="mx-auto mb-4 grid size-14 place-items-center rounded-full bg-brand text-2xl text-white">
              ✓
            </div>
            {!ready ? (
              <p className="text-soft">Loading your order…</p>
            ) : order?.name ? (
              <>
                <h1 className="font-heading text-[clamp(1.5rem,6vw,2rem)] text-brand">
                  Thank you, {order.name}
                </h1>
                <p className="mx-auto mt-3 max-w-md text-body">
                  Payment received for {order.quantity} Snackbar
                  {Number(order.quantity) === 1 ? "" : "s"} (₹
                  {Number(order.amount_rupees || 0).toLocaleString("en-IN")}).
                  We will ship to the address you shared.
                </p>
              </>
            ) : (
              <>
                <h1 className="font-heading text-2xl text-brand">Thank you</h1>
                <p className="mt-3 text-body">
                  No recent Snackbar order was found on this device.
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
              href="/snackbar"
              className="mt-6 inline-flex h-11 items-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white"
            >
              Back to Snackbar
            </Link>
          </div>
        </div>
      </section>
    </SiteShell>
  );
}
