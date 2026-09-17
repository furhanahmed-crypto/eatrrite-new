import { SiteShell } from "@/shared/components/SiteShell";
import { PageBanner } from "@/shared/components/PageBanner";
import { PillLabel } from "@/shared/components/PillLabel";
import { AppointmentForm } from "@/components/appointment/AppointmentForm";
import { siteConfig } from "@/config/site";

export const metadata = {
  title: "Book Appointment",
  description:
    "Book a consultation with Eat Rrite. Confirm your slot with a small fee and receive your Google Meet link.",
};

export default function AppointmentPage() {
  return (
    <SiteShell current="appointment">
      <PageBanner title="Book Appointment" pill="Appointment" />
      <section className="bg-cream py-16 md:py-24">
        <div className="container-er grid items-start gap-10 lg:grid-cols-[0.85fr_1.15fr]">
          <div className="space-y-4">
            <PillLabel>Consultation</PillLabel>
            <h1 className="font-heading text-4xl font-bold text-ink md:text-5xl">
              Reserve your slot
            </h1>
            <p className="max-w-md text-body">
              Pay a ₹{siteConfig.amountRupees} confirmation fee to lock your
              consultation. After payment, we finalize your Google Meet link.
            </p>
          </div>
          <div className="rounded-[20px] border border-border-soft bg-mint/60 p-5 shadow-er sm:p-7">
            <AppointmentForm />
          </div>
        </div>
      </section>
    </SiteShell>
  );
}
