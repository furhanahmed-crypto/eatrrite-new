export function PillLabel({ children, light = false }) {
  return (
    <span
      className={`inline-flex items-center gap-2 rounded-full px-4.5 py-2 text-sm font-medium tracking-[0.02em] ${
        light
          ? "border border-white/20 bg-white/15 text-white backdrop-blur-md"
          : "bg-[#eceeed] text-ink dark:bg-mint dark:text-ink"
      }`}
    >
      <span className="size-2 shrink-0 rounded-full bg-gold" />
      {children}
    </span>
  );
}
