import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { AboutStory } from "@/components/about/AboutStory";
import { AboutMission } from "@/components/about/AboutMission";
import { aboutContent } from "@/constants/about/aboutContent";

export const metadata = {
  title: "About Mukta Patil",
  description:
    "Meet Mukta Patil — nutritionist, speaker and founder of Eat Rrite.",
};

const sectionMap = {
  "about-story-section": AboutStory,
  "about-mission-section": AboutMission,
};

export default function AboutPage() {
  return (
    <SiteShell current="about">
      <PageBanner title="About Us" pill="About" />
      {aboutContent.map((section) => {
        const Component = sectionMap[section.name];
        if (!Component) return null;
        return <Component key={section.name} data={section} />;
      })}
    </SiteShell>
  );
}
