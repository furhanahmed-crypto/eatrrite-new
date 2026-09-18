import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarProduct({ data }) {
  return (
    <section id="inside" className="bg-mint py-16 md:py-24">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <div className="space-y-4">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="max-w-[48ch] text-lg text-ink">{data.lead}</p>
          </Reveal>
        </div>
        <div className="space-y-4">
          {data.items.map((item) => (
            <Reveal key={item.title} y={28} duration={0.8}>
              <article className="border-l-4 border-gold bg-brand px-5 py-4 text-white md:px-6">
                <h3 className="text-lg text-gold">{item.title}</h3>
                <p className="mt-1 text-sm text-white/85">{item.text}</p>
              </article>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
