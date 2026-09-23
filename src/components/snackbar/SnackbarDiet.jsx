import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";
import { BuyNowLink } from "@/components/snackbar/BuyNowLink";

export function SnackbarDiet({ data }) {
  return (
    <section className="border-b border-gold/25 bg-[linear-gradient(180deg,#fff8e8_0%,#f8f5f0_100%)] py-16 md:py-24">
      <div className="container-er grid items-start gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <div className="space-y-4">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="max-w-[52ch] text-lg text-body">{data.text}</p>
          </Reveal>
          <Reveal y={24} duration={0.8}>
            <BuyNowLink />
          </Reveal>
        </div>
        <Reveal y={32} duration={0.85}>
          <ul className="space-y-4 rounded-[24px] border border-gold/50 bg-white p-6">
            {data.points.map((point) => (
              <li
                key={point}
                className="border-l-2 border-gold pl-4 text-brand"
              >
                {point}
              </li>
            ))}
          </ul>
        </Reveal>
      </div>
    </section>
  );
}
