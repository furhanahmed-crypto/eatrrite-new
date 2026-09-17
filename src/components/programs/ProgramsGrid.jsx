import Image from "next/image";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";

export function ProgramsGrid({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <PillLabel>{data.pill}</PillLabel>
          <h2 className="text-3xl md:text-4xl">{data.title}</h2>
          <p>{data.lead}</p>
        </div>
        <div className="grid gap-6 sm:grid-cols-2">
          {data.programs.map((program) => (
            <article
              key={program.slug}
              className="overflow-hidden rounded-[24px] border border-border-soft bg-surface shadow-er"
            >
              <div className="relative aspect-[16/10]">
                <Image src={program.image} alt={program.short} fill className="object-cover" />
              </div>
              <div className="space-y-3 p-4 min-[400px]:p-6">
                <h3 className="text-xl min-[400px]:text-2xl">{program.short}</h3>
                <p>{program.summary}</p>
                <Link
                  href={`/programs/${program.slug}`}
                  className="inline-flex items-center gap-2 font-semibold text-brand"
                >
                  View Program <ArrowRight className="size-4" />
                </Link>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
