import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";
import { BuyNowLink } from "@/components/snackbar/BuyNowLink";

export function SnackbarBenefits({ data }) {
  return (
    <section className="border-b border-gold/25 bg-[#fffdf6] py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
        </div>
        <div className="grid gap-5 md:grid-cols-3">
          {data.items.map((item) => (
            <Reveal key={item.num} y={48} duration={0.9}>
              <article className="h-full rounded-[24px] border border-gold/50 bg-white p-6">
                <span className="inline-flex rounded-full border border-gold bg-gold/15 px-3 py-1 text-xs font-semibold tracking-[0.16em] text-brand">
                  {item.num}
                </span>
                <h3 className="mt-3 text-lg text-brand">{item.title}</h3>
                <p className="mt-2 text-sm text-body">{item.text}</p>
              </article>
            </Reveal>
          ))}
        </div>
        <div className="flex justify-center">
          <BuyNowLink />
        </div>
      </div>
    </section>
  );
}
