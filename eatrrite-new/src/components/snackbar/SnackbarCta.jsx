import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarCta({ data }) {
  return (
    <section className="bg-[#fffdf6] py-16 md:py-24">
      <div className="container-er">
        <Reveal y={48} duration={0.95} amount={0.3}>
          <div className="flex flex-col items-start justify-between gap-6 rounded-[24px] border-2 border-gold bg-[linear-gradient(180deg,#fff8e8_0%,#ffffff_100%)] px-5 py-10 min-[400px]:rounded-[28px] min-[400px]:px-8 min-[400px]:py-12 md:flex-row md:items-center md:px-12">
            <div className="min-w-0 space-y-3">
              <PillLabel>{data.pill}</PillLabel>
              <SplitTitle
                as="h2"
                className="text-[clamp(1.5rem,7vw,2.25rem)] text-brand md:text-4xl"
              >
                {data.title}
              </SplitTitle>
              <p className="text-body">{data.text}</p>
            </div>
            <Link
              href={data.cta.href}
              className="inline-flex h-11 shrink-0 items-center rounded-full border border-gold bg-gold px-5 font-heading text-[15px] font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a] min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
            >
              {data.cta.label}
            </Link>
          </div>
        </Reveal>
      </div>
    </section>
  );
}
