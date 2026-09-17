export function AboutMission({ data }) {
  return (
    <section className="bg-mint py-16 md:py-24">
      <div className="container-er grid gap-6 md:grid-cols-3">
        {data.cards.map((card) => (
          <article
            key={card.title}
            className="rounded-[24px] border border-border-soft bg-surface p-6 shadow-er"
          >
            <h3 className="mb-3 text-2xl">{card.title}</h3>
            <p>{card.text}</p>
          </article>
        ))}
      </div>
    </section>
  );
}
