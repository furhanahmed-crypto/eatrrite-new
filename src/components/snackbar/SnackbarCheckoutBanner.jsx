import Link from "next/link";

export function SnackbarCheckoutBanner() {
  return (
    <section className="relative overflow-hidden border-b-2 border-gold bg-[linear-gradient(180deg,#fff8e8_0%,#f8f5f0_100%)] py-12 text-center md:py-16">
      <div
        className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(229,184,88,0.28),transparent_60%)]"
        aria-hidden
      />
      <div className="container-er relative z-10 space-y-4">
        <nav aria-label="Breadcrumb">
          <ol className="flex flex-wrap items-center justify-center gap-2 text-[11px] font-semibold tracking-[0.22em] text-brand/55 uppercase min-[400px]:text-[12px]">
            <li>
              <Link href="/" className="transition hover:text-gold">
                Home
              </Link>
            </li>
            <li aria-hidden className="text-gold">
              ◆
            </li>
            <li>
              <Link href="/snackbar" className="transition hover:text-gold">
                Snackbar
              </Link>
            </li>
            <li aria-hidden className="text-gold">
              ◆
            </li>
            <li className="rounded-full border border-gold bg-gold/20 px-3 py-1 text-ink">
              Checkout
            </li>
          </ol>
        </nav>
        <h1 className="font-heading text-[clamp(2rem,6vw,3.4rem)] leading-[1.1] font-semibold text-brand">
          Checkout
        </h1>
        <span className="mx-auto block h-px w-24 bg-gold" />
      </div>
    </section>
  );
}
