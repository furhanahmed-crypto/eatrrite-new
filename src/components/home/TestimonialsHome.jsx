"use client";

import Image from "next/image";
import { Quote, Star } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function TestimonialsHome({ data }) {
  return (
    <section className="bg-cream py-16 md:py-24" id="testimonials">
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
        <div className="grid gap-6 md:grid-cols-3">
          {data.items.map((item) => (
            <Reveal key={item.name} y={56} duration={0.9} amount={0.3}>
              <article className="relative h-full rounded-[24px] border border-border-soft bg-surface p-6 shadow-er">
                <Quote className="absolute top-5 right-5 size-8 text-gold/40" />
                <Image
                  src={item.image}
                  alt={item.name}
                  width={72}
                  height={72}
                  className="size-18 rounded-full object-cover"
                />
                <div className="mt-4 flex gap-1 text-gold">
                  {Array.from({ length: 5 }).map((_, i) => (
                    <Star key={i} className="size-4 fill-current" />
                  ))}
                </div>
                <p className="mt-4 text-sm text-body">“{item.quote}”</p>
                <h4 className="mt-4 text-lg">{item.name}</h4>
                <span className="text-sm text-soft">{item.role}</span>
              </article>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
