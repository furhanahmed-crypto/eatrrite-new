import Image from "next/image";
import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarHero({ data }) {
  return (
    <section className="bg-brand py-12 text-white md:py-20">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <div className="space-y-5">
          <Reveal y={24} duration={0.7}>
            <PillLabel light>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle
            as="h1"
            hero
            className="max-w-[14em] text-[clamp(32px,4.6vw,52px)] font-bold leading-[1.15] text-white"
          >
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8} delay={0.1}>
            <p className="max-w-[48ch] text-[clamp(15px,1.5vw,17px)] leading-[1.65] text-white/90">
              {data.text}
            </p>
          </Reveal>
          <Reveal y={24} duration={0.8} delay={0.15}>
            <div className="flex flex-wrap items-center gap-4">
              <Link
                href={data.primaryCta.href}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex h-11 items-center rounded-full bg-gold px-5 font-heading text-[15px] font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a] min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
              >
                {data.primaryCta.label}
              </Link>
              <Link
                href={data.secondaryCta.href}
                className="inline-flex h-11 items-center rounded-full border border-gold/70 px-5 font-heading text-[15px] font-semibold text-gold transition hover:-translate-y-0.5 hover:bg-gold hover:text-ink min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
              >
                {data.secondaryCta.label}
              </Link>
            </div>
          </Reveal>
        </div>
        <Reveal y={40} duration={0.95} className="order-first lg:order-last">
          <div className="relative aspect-[4/3] overflow-hidden rounded-[24px] border-4 border-gold shadow-er">
            <Image
              src={data.image}
              alt={data.imageAlt}
              fill
              priority
              className="object-cover"
            />
          </div>
        </Reveal>
      </div>
    </section>
  );
}
