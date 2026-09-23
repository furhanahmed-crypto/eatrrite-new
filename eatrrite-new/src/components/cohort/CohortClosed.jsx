import Link from "next/link";
import { siteConfig } from "@/config/site";
import { PillLabel } from "@/shared/components/PillLabel";

export function CohortClosed({ title = "This cohort is full" }) {
  return (
    <section className="bg-cream py-16 md:py-24">
      <div className="container-er max-w-2xl">
        <div className="rounded-[20px] border border-border-soft bg-surface px-5 py-10 text-center shadow-er min-[400px]:px-8">
          <PillLabel>10 spots</PillLabel>
          <h1 className="mt-5 font-heading text-[clamp(1.75rem,6vw,2.5rem)] font-bold text-brand">
            {title}
          </h1>
          <p className="mx-auto mt-4 max-w-md text-body">
            All places in this cohort have been taken. WhatsApp us if you would
            like to be considered for the next one — or book a regular consult
            in the meantime.
          </p>
          <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a
              href={siteConfig.whatsapp}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex h-11 items-center rounded-full bg-gold px-7 font-heading text-[15px] font-semibold text-ink"
            >
              WhatsApp us
            </a>
            <Link
              href="/appointment"
              className="inline-flex h-11 items-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white"
            >
              Book a regular consult
            </Link>
          </div>
        </div>
      </div>
    </section>
  );
}
