"use client";

import Image from "next/image";
import { ArrowRight } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function BlogHome({ data }) {
  return (
    <section className="bg-background py-16 md:py-24" id="blog">
      <div className="container-er space-y-10">
        <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div className="space-y-3">
            <Reveal y={24} duration={0.7} amount={0.35}>
              <PillLabel>{data.pill}</PillLabel>
            </Reveal>
            <SplitTitle className="max-w-xl text-3xl md:text-4xl">
              {data.title}
            </SplitTitle>
          </div>
          <Reveal y={24} duration={0.7} amount={0.35}>
            <p className="max-w-md text-soft">{data.lead}</p>
          </Reveal>
        </div>
        <div className="grid gap-6 md:grid-cols-3">
          {data.posts.map((post) => (
            <Reveal key={post.title} y={56} duration={0.9} amount={0.3}>
              <article className="overflow-hidden rounded-[24px] border border-border-soft bg-surface shadow-er">
                <div className="relative aspect-[4/3]">
                  <Image src={post.image} alt={post.title} fill className="object-cover" />
                  <span className="absolute top-4 left-4 grid size-12 place-items-center rounded-2xl bg-brand text-lg font-bold text-white">
                    {post.date}
                  </span>
                </div>
                <div className="space-y-3 p-5">
                  <h3 className="text-xl text-ink">{post.title}</h3>
                  <p className="text-sm text-soft">{post.text}</p>
                  <span className="inline-flex items-center gap-2 text-sm font-semibold text-brand">
                    Read More <ArrowRight className="size-4" />
                  </span>
                </div>
              </article>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
