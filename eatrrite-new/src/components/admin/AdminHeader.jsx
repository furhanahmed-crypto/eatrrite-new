import Link from "next/link";

const links = [
  { href: "/admin/appointments", key: "appointments", label: "Consultations" },
  { href: "/admin/cohort", key: "cohort", label: "Cohort applications" },
];

export function AdminHeader({ current = "appointments" }) {
  return (
    <header className="sticky top-0 z-40 border-b border-border-soft bg-background/95 backdrop-blur">
      <div className="container-er flex max-w-5xl flex-wrap items-center justify-between gap-3 py-3 min-[400px]:py-4">
        <div className="min-w-0 space-y-2">
          <p className="text-[10px] tracking-[0.2em] text-brand uppercase min-[400px]:text-xs">
            Admin
          </p>
          <nav className="flex flex-wrap gap-2" aria-label="Admin sections">
            {links.map((link) => {
              const active = current === link.key;
              return (
                <Link
                  key={link.key}
                  href={link.href}
                  className={`inline-flex h-9 items-center rounded-full px-4 text-sm font-semibold transition ${
                    active
                      ? "bg-brand text-white"
                      : "bg-mint text-brand hover:bg-brand/10"
                  }`}
                >
                  {link.label}
                </Link>
              );
            })}
          </nav>
        </div>
        <Link
          href="/"
          className="inline-flex h-9 shrink-0 items-center rounded-lg border border-border px-3 text-sm font-medium hover:bg-muted"
        >
          Back to website
        </Link>
      </div>
    </header>
  );
}
