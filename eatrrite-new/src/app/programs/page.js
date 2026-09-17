import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { ProgramsGrid } from "@/components/programs/ProgramsGrid";
import { programsPageContent } from "@/constants/programs/programsPageContent";

export const metadata = {
  title: "Nutrition Programs",
  description:
    "Explore Eat Rrite's 30, 90 and 180-day nutrition programs for weight loss, diabetes, gut health and women's hormonal health.",
};

export default function ProgramsPage() {
  const section = programsPageContent[0];
  return (
    <SiteShell current="programs">
      <PageBanner title="Programs" pill="Programs" />
      <ProgramsGrid data={section} />
    </SiteShell>
  );
}
