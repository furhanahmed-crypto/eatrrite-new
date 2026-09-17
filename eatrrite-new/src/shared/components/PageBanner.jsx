import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";

export function PageBanner({ title, pill = "Eat Rrite" }) {
  return (
    <section className="bg-brand py-16 text-white md:py-20">
      <div className="container-er space-y-5">
        <PillLabel light>{pill}</PillLabel>
        <h1 className="max-w-3xl text-[clamp(32px,4vw,48px)] font-bold text-white">
          {title}
        </h1>
        <Link
          href="/appointment"
          className="inline-flex h-12 items-center rounded-full bg-gold px-7 font-heading text-base font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a]"
        >
          Get Consultation
        </Link>
      </div>
    </section>
  );
}
