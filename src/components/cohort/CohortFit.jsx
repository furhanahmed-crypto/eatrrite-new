import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function CohortFit({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="text-body">{data.lead}</p>
          </Reveal>
        </div>
        <div className="flex flex-wrap justify-center gap-3">
          {data.items.map((item) => (
            <Reveal key={item} y={20} duration={0.6}>
              <span className="inline-flex rounded-full border border-border-soft bg-mint px-4 py-2 text-sm font-medium text-brand">
                {item}
              </span>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
