"use client";

import {
  ClipboardCheck,
  FlaskConical,
  Flower2,
  Heart,
  Soup,
} from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

const icons = {
  Bowl: Soup,
  Heart,
  Flask: FlaskConical,
  Spa: Flower2,
  Clipboard: ClipboardCheck,
};

export function WhyChooseHome({ data }) {
  return (
    <section className="bg-mint py-16 md:py-24" id="why-choose">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <Reveal y={24} duration={0.7} amount={0.35}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl md:text-4xl">{data.title}</SplitTitle>
        </div>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
          {data.items.map((item) => {
            const Icon = icons[item.icon] || Heart;
            return (
              <Reveal key={item.num} y={56} duration={0.9} amount={0.3}>
                <article className="h-full rounded-[24px] border border-border-soft bg-surface p-5 shadow-er">
                  <div className="mb-2 flex items-center gap-3">
                    <div className="grid size-11 shrink-0 place-items-center rounded-2xl bg-mint text-brand">
                      <Icon className="size-5" />
                    </div>
                    <div>
                      <span className="text-xs font-bold tracking-widest text-gold">
                        {item.num}
                      </span>
                      <h3 className="text-lg leading-snug">{item.title}</h3>
                    </div>
                  </div>
                  <p className="text-sm text-soft">{item.text}</p>
                </article>
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
