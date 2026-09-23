import Image from "next/image";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

export function CohortGuide({ data }) {
  return (
    <section className="bg-mint py-16 md:py-24">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <Reveal y={32} duration={0.85}>
          <div className="relative aspect-[4/5] overflow-hidden rounded-[24px]">
            <Image
              src={data.image}
              alt={data.imageAlt}
              fill
              className="object-cover"
            />
          </div>
        </Reveal>
        <div className="space-y-4">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="max-w-[52ch] text-body">{data.text}</p>
          </Reveal>
          <Reveal y={24} duration={0.8}>
            <ul className="space-y-3 border-l-4 border-gold pl-5">
              {data.points.map((point) => (
                <li key={point} className="text-sm text-brand">
                  {point}
                </li>
              ))}
            </ul>
          </Reveal>
        </div>
      </div>
    </section>
  );
}
