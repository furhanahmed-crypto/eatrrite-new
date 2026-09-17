"use client";

import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function CtaHome({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er">
        <Reveal y={48} duration={0.95} amount={0.3}>
          <div className="flex flex-col items-start justify-between gap-8 rounded-[28px] bg-brand px-8 py-12 text-white md:flex-row md:items-center md:px-12">
            <div className="space-y-3">
              <PillLabel light>{data.pill}</PillLabel>
              <SplitTitle as="h2" className="text-3xl text-white md:text-4xl">
                {data.title}
              </SplitTitle>
              <p className="text-white/85">{data.text}</p>
            </div>
            <Link
              href={data.cta.href}
              className="inline-flex h-12 items-center rounded-full bg-gold px-7 font-heading text-base font-semibold text-ink transition hover:-translate-y-0.5"
            >
              {data.cta.label}
            </Link>
          </div>
        </Reveal>
      </div>
    </section>
  );
}
