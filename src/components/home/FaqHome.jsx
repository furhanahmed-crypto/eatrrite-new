"use client";

import { useState } from "react";
import Image from "next/image";
import { ChevronDown } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function FaqHome({ data }) {
  const [open, setOpen] = useState(0);

  return (
    <section className="bg-background py-16 md:py-24" id="faqs">
      <div className="container-er">
        <div className="grid gap-10 rounded-[28px] border border-border-soft bg-cream p-6 md:grid-cols-[0.9fr_1.1fr] md:p-10">
          <div className="space-y-4">
            <Reveal y={24} duration={0.7} amount={0.35}>
              <PillLabel>{data.pill}</PillLabel>
            </Reveal>
            <SplitTitle className="text-3xl md:text-4xl">{data.title}</SplitTitle>
            <Reveal y={40} duration={0.95} amount={0.3}>
              <div className="relative mx-auto mt-6 aspect-square max-w-xs">
                <Image src={data.image} alt="" fill className="object-contain" />
              </div>
            </Reveal>
          </div>
          <div className="space-y-3">
            {data.items.map((item, index) => {
              const isOpen = open === index;
              return (
                <Reveal key={item.q} y={56} duration={0.9} amount={0.3}>
                  <article className="overflow-hidden rounded-2xl border border-border-soft bg-surface">
                    <button
                      type="button"
                      className="flex w-full items-center justify-between gap-3 px-4 py-4 text-left font-medium text-ink"
                      onClick={() => setOpen(isOpen ? -1 : index)}
                    >
                      <span>{item.q}</span>
                      <ChevronDown
                        className={`size-4 shrink-0 transition ${isOpen ? "rotate-180" : ""}`}
                      />
                    </button>
                    <div
                      className={`grid transition-[grid-template-rows] duration-300 ${
                        isOpen ? "grid-rows-[1fr]" : "grid-rows-[0fr]"
                      }`}
                    >
                      <div className="overflow-hidden">
                        <p className="px-4 pb-4 text-sm text-soft">{item.a}</p>
                      </div>
                    </div>
                  </article>
                </Reveal>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
}
