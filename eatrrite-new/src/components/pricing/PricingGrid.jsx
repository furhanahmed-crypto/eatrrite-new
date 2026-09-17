import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";

export function PricingGrid({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <PillLabel>{data.pill}</PillLabel>
          <h2 className="text-3xl md:text-4xl">{data.title}</h2>
          <p>{data.lead}</p>
        </div>
        <div className="grid gap-6 md:grid-cols-3">
          {data.packages.map((item) => (
            <article
              key={item.name}
              className={`rounded-[24px] border bg-surface p-8 text-center ${
                item.featured
                  ? "border-gold shadow-er"
                  : "border-border-soft"
              }`}
            >
              {item.featured ? (
                <span className="mb-3 inline-block rounded-full bg-gold px-3 py-1 text-xs font-bold">
                  Most Popular
                </span>
              ) : null}
              <h3 className="text-2xl">{item.name}</h3>
              <p className="mt-3 mb-6">{item.blurb}</p>
              <Link
                href="/appointment"
                className="inline-flex h-10 items-center rounded-full bg-brand px-5 text-sm font-semibold text-white"
              >
                Get Consultation
              </Link>
            </article>
          ))}
        </div>
        <p className="mx-auto max-w-3xl text-center text-sm text-soft">
          {data.note}
        </p>
      </div>
    </section>
  );
}
