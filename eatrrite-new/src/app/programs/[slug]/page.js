import Image from "next/image";
import Link from "next/link";
import { notFound } from "next/navigation";
import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { PillLabel } from "@/shared/components/PillLabel";
import { findProgram, programs } from "@/constants/programs/programs";
import { packages } from "@/constants/programs/packages";

export function generateStaticParams() {
  return programs.map((program) => ({ slug: program.slug }));
}

export async function generateMetadata({ params }) {
  const { slug } = await params;
  const program = findProgram(slug);
  if (!program) return {};
  return { title: program.name, description: program.summary };
}

export default async function ProgramDetailPage({ params }) {
  const { slug } = await params;
  const program = findProgram(slug);
  if (!program) notFound();

  return (
    <SiteShell current="programs">
      <PageBanner title={program.short} pill="Program" />
      <section className="py-16">
        <div className="container-er grid items-center gap-10 lg:grid-cols-2">
          <div className="space-y-4">
            <PillLabel>About the Program</PillLabel>
            <h2 className="text-3xl md:text-4xl">{program.name}</h2>
            <p>{program.about}</p>
            <Link
              href="/appointment"
              className="inline-flex h-10 items-center rounded-full bg-gold px-6 text-sm font-semibold text-ink"
            >
              Get Consultation
            </Link>
          </div>
          <div className="relative aspect-[4/3] overflow-hidden rounded-[24px]">
            <Image src={program.image} alt={program.short} fill className="object-cover" />
          </div>
        </div>
      </section>
      <section className="bg-mint py-16">
        <div className="container-er grid gap-10 lg:grid-cols-2">
          <div className="relative aspect-[4/3] overflow-hidden rounded-[24px]">
            <Image
              src={program.imageSecondary}
              alt={program.short}
              fill
              className="object-cover"
            />
          </div>
          <div className="space-y-4">
            <h3 className="text-2xl">Key Benefits</h3>
            <ul className="space-y-2">
              {program.benefits.map((item) => (
                <li key={item}>✓ {item}</li>
              ))}
            </ul>
            <h3 className="text-2xl">Ideal For</h3>
            <p>{program.ideal}</p>
          </div>
        </div>
      </section>
      <section className="py-16">
        <div className="container-er space-y-8">
          <div className="mx-auto max-w-2xl text-center">
            <PillLabel>Packages</PillLabel>
            <h2 className="mt-3 text-3xl">Choose your program length</h2>
          </div>
          <div className="grid gap-6 md:grid-cols-3">
            {packages.map((item) => (
              <article
                key={item.name}
                className={`rounded-[24px] border p-6 text-center ${
                  item.featured
                    ? "border-gold shadow-er"
                    : "border-border-soft"
                }`}
              >
                <h3 className="text-xl">{item.name}</h3>
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
        </div>
      </section>
    </SiteShell>
  );
}
