"use client";

import Image from "next/image";
import { motion } from "framer-motion";
import { siteConfig } from "@/config/site";

const price = siteConfig.snackbarAmountRupees.toLocaleString("en-IN");

export function SnackbarCheckoutProduct() {
  return (
    <div className="space-y-5">
      <p className="inline-flex items-center gap-2 rounded-full border border-gold bg-gold/15 px-3.5 py-1.5 text-xs font-semibold tracking-[0.14em] text-ink uppercase">
        <span className="size-1.5 rounded-full bg-gold" />
        Limited bar
      </p>
      <motion.div
        className="group relative overflow-hidden rounded-[24px] border-2 border-gold bg-white shadow-[0_18px_50px_rgba(229,184,88,0.18)]"
        whileHover={{ y: -4 }}
        transition={{ duration: 0.35, ease: [0.22, 1, 0.36, 1] }}
      >
        <div className="relative aspect-[4/3]">
          <Image
            src="/images/snackbar/hero.png"
            alt="Eat Rrite Snackbar"
            fill
            priority
            className="object-cover transition duration-500 group-hover:scale-[1.04]"
          />
        </div>
        <div className="absolute top-3 right-3 rounded-full border border-gold bg-white/95 px-3 py-1 font-heading text-sm font-semibold text-brand shadow-sm">
          ₹{price}
        </div>
      </motion.div>
      <div className="space-y-2 rounded-[20px] border border-gold/50 bg-white/80 px-4 py-4">
        <h2 className="font-heading text-[clamp(1.6rem,5vw,2.25rem)] font-bold text-brand">
          Eat Rrite Snackbar
        </h2>
        <p className="max-w-md text-body">
          One bar is ₹{price}. Choose quantity, add your delivery details, then
          complete payment. We ship after a successful payment.
        </p>
      </div>
    </div>
  );
}
