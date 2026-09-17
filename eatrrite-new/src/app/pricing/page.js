import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { PricingGrid } from "@/components/pricing/PricingGrid";
import { pricingContent } from "@/constants/pricing/pricingContent";

export const metadata = {
  title: "Pricing",
  description:
    "Eat Rrite nutrition programs come in three package lengths — 30, 90 and 180 days.",
};

export default function PricingPage() {
  return (
    <SiteShell current="pricing">
      <PageBanner title="Pricing" pill="Pricing" />
      <PricingGrid data={pricingContent[0]} />
    </SiteShell>
  );
}
