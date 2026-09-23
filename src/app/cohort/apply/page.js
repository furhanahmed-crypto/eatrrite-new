import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { PillLabel } from "@/shared/components/PillLabel";
import { CohortApplyForm } from "@/components/cohort/CohortApplyForm";
import { CohortClosed } from "@/components/cohort/CohortClosed";
import { siteConfig } from "@/config/site";
import { currentCohortMonth } from "@/config/cohort";
import { countPaidCohortApplications } from "@/lib/db/cohort-applications";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "Apply for the Cohort",
  description:
    "Pay the consultation fee and reserve a complimentary first month in the Eat Rrite cohort.",
};

export default async function CohortApplyPage() {
  const taken = await countPaidCohortApplications(currentCohortMonth());
  const remaining = Math.max(0, siteConfig.cohortSpots - taken);
  const fee = siteConfig.cohortConsultationRupees.toLocaleString("en-IN");
  const monthly = siteConfig.cohortMonthlyRupees.toLocaleString("en-IN");

  return (
    <SiteShell current="cohort">
      <PageBanner title="Apply for the Cohort" crumb="Cohort" />
      {remaining <= 0 ? (
        <CohortClosed title="This month's cohort is full" />
      ) : (
        <section className="bg-cream py-12 md:py-24">
          <div className="container-er grid items-start gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:gap-10">
            <div className="space-y-4">
              <PillLabel>{remaining} spots left</PillLabel>
              <h1 className="font-heading text-[clamp(1.75rem,8vw,3rem)] font-bold leading-tight text-ink">
                Pay the consultation fee. Claim your first month free.
              </h1>
              <p className="max-w-md text-body">
                A one-time ₹{fee} consultation reserves your place — and unlocks
                a complimentary first month in the ₹{monthly} cohort. From month
                two, continue or step away. No lock-in.
              </p>
            </div>
            <div className="rounded-[20px] border border-border-soft bg-mint/60 p-4 shadow-er min-[400px]:p-5 sm:p-7">
              <CohortApplyForm />
            </div>
          </div>
        </section>
      )}
    </SiteShell>
  );
}
