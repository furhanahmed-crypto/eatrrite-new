"use client";

import Image from "next/image";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function ProgramsHome({ data }) {
  return (
    <section className="bg-mint py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <Reveal y={24} duration={0.7} amount={0.35}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl md:text-4xl">{data.title}</SplitTitle>
          <Reveal y={24} duration={0.7} amount={0.35}>
            <p className="text-soft">{data.lead}</p>
          </Reveal>
        </div>
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {data.programs.map((program) => (
            <Reveal key={program.slug} y={56} duration={0.9} amount={0.3}>
              <article className="h-full overflow-hidden rounded-[24px] border border-border-soft bg-surface shadow-er">
                <div className="relative aspect-[4/3]">
                  <Image
                    src={program.image}
                    alt={program.short}
                    fill
                    className="object-cover"
                  />
                </div>
                <div className="space-y-3 p-5">
                  <h3 className="text-xl text-ink">{program.short}</h3>
                  <p className="text-sm text-soft">{program.summary}</p>
                  <Link
                    href={`/programs/${program.slug}`}
                    className="inline-flex items-center gap-2 text-sm font-semibold text-brand"
                  >
                    Learn More <ArrowRight className="size-4" />
                  </Link>
                </div>
              </article>
            </Reveal>
          ))}
        </div>
        <div className="text-center">
          <Link
            href={data.cta.href}
            className="inline-flex h-10 items-center rounded-full bg-brand px-6 text-sm font-semibold text-white"
          >
            {data.cta.label}
          </Link>
        </div>
      </div>
    </section>
  );
}
