import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";
import { BuyNowLink } from "@/components/snackbar/BuyNowLink";

export function SnackbarProduct({ data }) {
  return (
    <section
      id="inside"
      className="border-b border-gold/25 bg-[#fffdf6] py-16 md:py-24"
    >
      <div className="container-er grid items-center gap-10 lg:grid-cols-2">
        <div className="space-y-4">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
          <Reveal y={24} duration={0.8}>
            <p className="max-w-[48ch] text-lg text-body">{data.lead}</p>
          </Reveal>
          <Reveal y={24} duration={0.8}>
            <BuyNowLink />
          </Reveal>
        </div>
        <div className="space-y-4">
          {data.items.map((item) => (
            <Reveal key={item.title} y={28} duration={0.8}>
              <article className="rounded-[20px] border border-gold/50 bg-white px-5 py-4 md:px-6">
                <h3 className="text-lg text-brand">{item.title}</h3>
                <p className="mt-1 text-sm text-body">{item.text}</p>
              </article>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
