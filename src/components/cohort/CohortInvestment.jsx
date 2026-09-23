import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function CohortInvestment({ data }) {
  return (
    <section className="bg-cream py-16 md:py-24">
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
        <div className="grid gap-5 md:grid-cols-3">
          {data.cards.map((card) => (
            <Reveal key={card.label} y={48} duration={0.9}>
              <article className="h-full rounded-[24px] bg-surface p-6 shadow-er ring-1 ring-brand/15">
                <span className="text-xs font-bold tracking-[0.08em] text-soft uppercase">
                  {card.label}
                </span>
                <p className="mt-2 font-heading text-3xl font-bold text-brand">
                  {card.value}
                </p>
                <p className="mt-3 text-sm text-body">{card.text}</p>
              </article>
            </Reveal>
          ))}
        </div>
        <p className="text-center text-sm text-soft">{data.note}</p>
      </div>
    </section>
  );
}
