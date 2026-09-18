import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function SnackbarDiet({ data }) {
  return (
    <section className="bg-mint py-16 md:py-24">
      <div className="container-er grid items-start gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <div className="space-y-4">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="max-w-[52ch] text-lg text-ink">{data.text}</p>
          </Reveal>
        </div>
        <Reveal y={32} duration={0.85}>
          <ul className="space-y-5 border-l-4 border-gold pl-5 md:pl-7">
            {data.points.map((point) => (
              <li key={point} className="text-brand">
                {point}
              </li>
            ))}
          </ul>
        </Reveal>
      </div>
    </section>
  );
}
