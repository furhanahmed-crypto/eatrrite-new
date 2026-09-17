export function MarqueeHome({ data }) {
  const items = [...data.items, ...data.items];

  return (
    <div className="overflow-hidden border-y border-border-soft bg-mint py-4" aria-hidden>
      <div className="marquee-track flex w-max gap-10 whitespace-nowrap">
        {items.map((item, index) => (
          <span
            key={`${item}-${index}`}
            className="font-heading text-lg font-medium tracking-wide text-ink md:text-xl"
          >
            {item}
            <span className="ml-10 text-gold">•</span>
          </span>
        ))}
      </div>
    </div>
  );
}
