import { SiteShell } from "@/shared/components/SiteShell";
import { SnackbarHero } from "@/components/snackbar/SnackbarHero";
import { SnackbarProduct } from "@/components/snackbar/SnackbarProduct";
import { SnackbarIngredients } from "@/components/snackbar/SnackbarIngredients";
import { SnackbarBenefits } from "@/components/snackbar/SnackbarBenefits";
import { SnackbarDiet } from "@/components/snackbar/SnackbarDiet";
import { SnackbarCta } from "@/components/snackbar/SnackbarCta";
import { snackbarContent } from "@/constants/snackbar/snackbarContent";

export const metadata = {
  title: "Snackbar",
  description:
    "Snackbar by Eat Rrite — one diet-perfect bar of dates, oats and almonds.",
};

const sectionMap = {
  "hero-section": SnackbarHero,
  "product-section": SnackbarProduct,
  "ingredients-section": SnackbarIngredients,
  "benefits-section": SnackbarBenefits,
  "diet-section": SnackbarDiet,
  "cta-section": SnackbarCta,
};

export default function SnackbarPage() {
  return (
    <SiteShell current="snackbar">
      {snackbarContent.map((section) => {
        const Component = sectionMap[section.name];
        if (!Component) return null;
        return <Component key={section.name} data={section} />;
      })}
    </SiteShell>
  );
}
