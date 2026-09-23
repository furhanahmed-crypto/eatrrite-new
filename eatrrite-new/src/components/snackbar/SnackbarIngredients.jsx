import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";
import { BuyNowLink } from "@/components/snackbar/BuyNowLink";

export function SnackbarIngredients({ data }) {
  return (
    <section className="border-b border-gold/25 bg-[linear-gradient(180deg,#fff8e8_0%,#f8f5f0_100%)] py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="max-w-2xl space-y-3">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="text-lg text-body">{data.lead}</p>
          </Reveal>
        </div>
        <Reveal y={32} duration={0.85}>
          <ol className="overflow-hidden rounded-[24px] border border-gold/50 bg-white">
            {data.items.map((item, index) => (
              <li
                key={item.name}
                className="grid gap-2 border-b border-gold/20 px-5 py-5 last:border-b-0 min-[400px]:px-8 md:grid-cols-[8rem_1fr] md:items-baseline md:gap-8"
              >
                <span className="font-heading text-xl text-brand">
                  {index + 1}. {item.name}
                </span>
                <p className="text-sm text-body md:text-base">{item.text}</p>
              </li>
            ))}
          </ol>
        </Reveal>
        <BuyNowLink />
      </div>
    </section>
  );
}
