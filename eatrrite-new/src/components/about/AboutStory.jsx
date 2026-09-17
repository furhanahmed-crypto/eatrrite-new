import Image from "next/image";
import { PillLabel } from "@/shared/components/PillLabel";

export function AboutStory({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <div className="relative aspect-[4/5] overflow-hidden rounded-[24px]">
          <Image src={data.image} alt="Mukta Patil" fill className="object-cover" />
        </div>
        <div className="space-y-4">
          <PillLabel>{data.pill}</PillLabel>
          <h2 className="text-3xl md:text-4xl">{data.title}</h2>
          {data.paragraphs.map((text) => (
            <p key={text}>{text}</p>
          ))}
        </div>
      </div>
    </section>
  );
}
