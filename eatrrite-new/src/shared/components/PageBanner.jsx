import Image from "next/image";
import Link from "next/link";

export function PageBanner({ title, crumb }) {
  const label = crumb || title;

  return (
    <section className="relative isolate overflow-hidden py-20 text-center text-white md:py-28">
      <Image
        src="/images/hero/healthy-plate.jpg"
        alt=""
        fill
        priority
        sizes="100vw"
        className="object-cover object-center"
      />
      <div className="absolute inset-0 bg-brand/80" aria-hidden />
      <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(229,184,88,0.18),transparent_55%)]" aria-hidden />

      <div className="container-er relative z-10 space-y-4">
        <nav
          aria-label="Breadcrumb"
          className="text-[13px] font-medium tracking-[0.12em] text-white/75 uppercase"
        >
          <ol className="flex flex-wrap items-center justify-center gap-2">
            <li>
              <Link href="/" className="transition hover:text-gold">
                Home
              </Link>
            </li>
            <li aria-hidden className="text-white/35">
              /
            </li>
            <li className="text-white">{label}</li>
          </ol>
        </nav>
        <h1 className="font-heading text-[clamp(2rem,5vw,3.75rem)] leading-[1.1] font-semibold text-white">
          {title}
        </h1>
      </div>
    </section>
  );
}
