import Image from "next/image";
import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarHero({ data }) {
  return (
    <section className="border-b border-gold/35 bg-[linear-gradient(180deg,#fff8e8_0%,#f8f5f0_100%)] py-12 md:py-20">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <div className="space-y-5">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle
            as="h1"
            hero
            className="max-w-[14em] text-[clamp(32px,4.6vw,52px)] font-bold leading-[1.15] text-brand"
          >
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8} delay={0.1}>
            <p className="max-w-[48ch] text-[clamp(15px,1.5vw,17px)] leading-[1.65] text-body">
              {data.text}
            </p>
          </Reveal>
          <Reveal y={24} duration={0.8} delay={0.15}>
            <div className="flex flex-wrap items-center gap-4">
              <Link
                href={data.primaryCta.href}
                className="inline-flex h-11 items-center rounded-full border border-gold bg-gold px-5 font-heading text-[15px] font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a] min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
              >
                {data.primaryCta.label}
              </Link>
              <Link
                href={data.secondaryCta.href}
                className="inline-flex h-11 items-center rounded-full border border-gold px-5 font-heading text-[15px] font-semibold text-brand transition hover:-translate-y-0.5 hover:bg-gold min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
              >
                {data.secondaryCta.label}
              </Link>
            </div>
          </Reveal>
        </div>
        <Reveal y={40} duration={0.95} className="order-first lg:order-last">
          <div className="relative aspect-[4/3] overflow-hidden rounded-[24px] border-2 border-gold bg-white shadow-[0_18px_50px_rgba(229,184,88,0.16)]">
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
