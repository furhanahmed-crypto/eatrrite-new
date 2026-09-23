import { SiteShell } from "@/shared/components/SiteShell";
import { CohortHero } from "@/components/cohort/CohortHero";
import { CohortGuide } from "@/components/cohort/CohortGuide";
import { CohortFit } from "@/components/cohort/CohortFit";
import { CohortInvestment } from "@/components/cohort/CohortInvestment";
import { CohortSteps } from "@/components/cohort/CohortSteps";
import { CohortCta } from "@/components/cohort/CohortCta";
import { cohortContent } from "@/constants/cohort/cohortContent";

export const metadata = {
  title: "Lifestyle Reversal Cohort",
  description:
    "Apply for Eat Rrite's women's hormonal health and lifestyle reversal cohort. First month free. ₹800 consultation.",
};

const sectionMap = {
  "hero-section": CohortHero,
  "guide-section": CohortGuide,
  "fit-section": CohortFit,
  "investment-section": CohortInvestment,
  "steps-section": CohortSteps,
  "cta-section": CohortCta,
};

export default function CohortPage() {
  return (
    <SiteShell current="cohort">
      {cohortContent.map((section) => {
        const Component = sectionMap[section.name];
        if (!Component) return null;
        return <Component key={section.name} data={section} />;
      })}
    </SiteShell>
  );
}
