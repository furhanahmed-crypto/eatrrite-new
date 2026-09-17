import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";

export function ContactPanel({ data }) {
  return (
    <section className="py-16 md:py-24">
      <div className="container-er grid gap-6 lg:grid-cols-2">
        <article className="rounded-[24px] border border-border-soft bg-surface p-8 shadow-er">
          <PillLabel>Get In Touch</PillLabel>
          <h2 className="mt-3 mb-4 text-3xl">{data.title}</h2>
          <p className="mb-6">{data.text}</p>
          <p className="mb-3">
            <strong>Phone / WhatsApp</strong>
            <br />
            <a href={data.phoneHref}>{data.phone}</a>
          </p>
          <p className="mb-3">
            <strong>Email</strong>
            <br />
            <a href={data.emailHref}>{data.email}</a>
          </p>
          <p className="mb-3">
            <strong>Based in</strong>
            <br />
            {data.address1} &amp; {data.address2}
            <br />
            <span className="text-sm text-soft">
              {data.locationsNote}
            </span>
          </p>
          <p>
            <strong>Hours</strong>
            <br />
            {data.hours}
          </p>
        </article>
        <article className="rounded-[24px] border border-border-soft bg-surface p-8 shadow-er">
          <h3 className="mb-3 text-2xl">Book or enquire</h3>
          <p className="mb-6">
            Share your name, phone, city and program of interest — we will take
            it from there.
          </p>
          <div className="mb-6 flex flex-wrap gap-3">
            <Link
              href="/appointment"
              className="inline-flex h-10 items-center rounded-full bg-gold px-5 text-sm font-semibold text-ink"
            >
              Get Consultation
            </Link>
            <a
              href={data.whatsapp}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex h-10 items-center rounded-full bg-brand px-5 text-sm font-semibold text-white"
            >
              Message on WhatsApp
            </a>
          </div>
          <ul className="space-y-2">
            {data.programs.map((name) => (
              <li key={name}>✓ {name}</li>
            ))}
          </ul>
        </article>
      </div>
    </section>
  );
}
