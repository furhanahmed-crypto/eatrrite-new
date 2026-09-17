"use client";

import Image from "next/image";
import Link from "next/link";
import { ArrowRight, Droplet, Heart, Leaf, Wheat, Venus } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

const icons = { Wheat, Droplet, Leaf, Venus, Heart };

export function ServicesHome({ data }) {
  return (
    <section className="bg-background py-16 md:py-24" id="services">
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
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {data.programs.map((program) => {
            const Icon = icons[program.icon] || Heart;
            return (
              <Reveal key={program.slug} y={56} duration={0.9} amount={0.3}>
                <article className="group relative overflow-hidden rounded-[24px] border border-border-soft bg-surface shadow-er">
                  <div className="relative aspect-[3/4]">
                    <Image
                      src={program.image}
                      alt={program.short}
                      fill
                      className="object-cover transition duration-500 group-hover:scale-105"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-brand/90 via-brand/40 to-transparent" />
                    <div className="absolute inset-x-0 bottom-0 space-y-3 p-5 text-white">
                      <div className="grid size-11 place-items-center rounded-full bg-gold text-ink">
                        <Icon className="size-5" />
                      </div>
                      <h3 className="text-xl text-white">{program.short}</h3>
                      <p className="text-sm text-white/85">{program.summary}</p>
                      <Link
                        href={`/programs/${program.slug}`}
                        className="inline-flex size-10 items-center justify-center rounded-full bg-white/15 text-white"
                        aria-label={`Learn more about ${program.short}`}
                      >
                        <ArrowRight className="size-4" />
                      </Link>
                    </div>
                  </div>
                </article>
              </Reveal>
            );
          })}
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
