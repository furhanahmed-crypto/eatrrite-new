"use client";

import Image from "next/image";
import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function AboutHome({ data }) {
  return (
    <section className="bg-cream py-16 md:py-24">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <Reveal y={40} duration={0.95} className="relative">
          <div className="relative aspect-[4/5] overflow-hidden rounded-[24px]">
            <Image src={data.image} alt="Mukta Patil" fill className="object-cover" />
          </div>
          <div className="absolute -right-2 -bottom-6 hidden w-40 overflow-hidden rounded-2xl border-4 border-surface shadow-er sm:block">
            <Image
              src={data.sideImage}
              alt="Nourishing bowl"
              width={160}
              height={160}
              className="h-auto w-full"
            />
          </div>
        </Reveal>
        <div className="space-y-5">
          <Reveal y={24} duration={0.7} amount={0.35}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl md:text-4xl">{data.title}</SplitTitle>
          <Reveal y={28} duration={0.8} amount={0.35}>
            <p className="text-lg text-ink">{data.lead}</p>
          </Reveal>
          <Reveal y={28} duration={0.8} amount={0.35}>
            <p className="text-body">{data.text}</p>
          </Reveal>
          <Reveal y={28} duration={0.8} amount={0.35}>
            <blockquote className="border-l-4 border-gold pl-4 italic text-ink">
              “{data.quote}”
              <cite className="mt-2 block text-sm not-italic text-soft">{data.cite}</cite>
            </blockquote>
          </Reveal>
          <Reveal y={28} duration={0.8} amount={0.35}>
            <Link
              href={data.cta.href}
              className="inline-flex h-10 items-center rounded-full bg-brand px-6 text-sm font-semibold text-white"
            >
              {data.cta.label}
            </Link>
          </Reveal>
        </div>
      </div>
    </section>
  );
}
