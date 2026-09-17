import { SiteShell } from "@/shared/components/SiteShell";
import { Hero } from "@/components/home/Hero";
import { ServicesHome } from "@/components/home/ServicesHome";
import { MarqueeHome } from "@/components/home/MarqueeHome";
import { AboutHome } from "@/components/home/AboutHome";
import { WhyChooseHome } from "@/components/home/WhyChooseHome";
import { ProcessHome } from "@/components/home/ProcessHome";
import { ProgramsHome } from "@/components/home/ProgramsHome";
import { FaqHome } from "@/components/home/FaqHome";
import { BlogHome } from "@/components/home/BlogHome";
import { TestimonialsHome } from "@/components/home/TestimonialsHome";
import { CtaHome } from "@/components/home/CtaHome";
import { homeContent } from "@/constants/home/homeContent";

export const metadata = {
  title: "Eat Rrite | Holistic Nutrition Coaching in Hyderabad & Dehradun",
  description:
    "Eat Rrite offers science-backed, culturally-rooted nutrition coaching for weight loss, PCOS, diabetes, gut health and more — without crash diets.",
};

const sectionMap = {
  "hero-section": Hero,
  "services-section": ServicesHome,
  "marquee-section": MarqueeHome,
  "about-home-section": AboutHome,
  "why-choose-section": WhyChooseHome,
  "process-section": ProcessHome,
  "programs-section": ProgramsHome,
  "faq-section": FaqHome,
  "blog-section": BlogHome,
  "testimonials-section": TestimonialsHome,
  "cta-section": CtaHome,
};

export default function HomePage() {
  return (
    <SiteShell current="home">
      {homeContent.map((section) => {
        const Component = sectionMap[section.name];
        if (!Component) return null;
        return <Component key={section.name} data={section} />;
      })}
    </SiteShell>
  );
}
