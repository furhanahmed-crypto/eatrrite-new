import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarIngredients({ data }) {
  return (
    <section className="bg-brand py-16 text-white md:py-24">
      <div className="container-er space-y-10">
        <div className="max-w-2xl space-y-3">
          <Reveal y={24} duration={0.7}>
            <PillLabel light>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-white md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="text-lg text-white/90">{data.lead}</p>
          </Reveal>
        </div>
        <Reveal y={32} duration={0.85}>
          <ol className="overflow-hidden rounded-[24px] bg-mint text-ink">
            {data.items.map((item, index) => (
              <li
                key={item.name}
                className="grid gap-2 border-b border-brand/10 px-5 py-5 last:border-b-0 min-[400px]:px-8 md:grid-cols-[8rem_1fr] md:items-baseline md:gap-8"
              >
                <span className="font-heading text-xl text-brand">
                  {index + 1}. {item.name}
                </span>
                <p className="text-sm text-body md:text-base">{item.text}</p>
              </li>
            ))}
          </ol>
        </Reveal>
      </div>
    </section>
  );
}
