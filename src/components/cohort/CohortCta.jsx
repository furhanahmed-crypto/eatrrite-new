import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";
import { ApplySpotLink } from "@/components/cohort/ApplySpotLink";

export function CohortCta({ data }) {
  return (
    <section className="bg-cream py-16 md:py-24">
      <div className="container-er">
        <Reveal y={48} duration={0.95}>
          <div className="flex flex-col items-start justify-between gap-6 rounded-[24px] bg-brand px-5 py-10 text-white min-[400px]:rounded-[28px] min-[400px]:px-8 min-[400px]:py-12 md:flex-row md:items-center md:px-12">
            <div className="min-w-0 space-y-3">
              <PillLabel light>{data.pill}</PillLabel>
              <SplitTitle
                as="h2"
                className="text-[clamp(1.5rem,7vw,2.25rem)] text-white md:text-4xl"
              >
                {data.title}
              </SplitTitle>
              <p className="text-white/85">{data.text}</p>
            </div>
            <ApplySpotLink
              href={data.cta.href}
              label={data.cta.label}
              showFee={false}
              className="shrink-0"
            />
          </div>
        </Reveal>
      </div>
    </section>
  );
}
