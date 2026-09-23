import { SiteShell } from "@/shared/components/SiteShell";
import { SnackbarCheckoutBanner } from "@/components/snackbar/SnackbarCheckoutBanner";
import { SnackbarCheckoutProduct } from "@/components/snackbar/SnackbarCheckoutProduct";
import { SnackbarCheckoutForm } from "@/components/snackbar/SnackbarCheckoutForm";

export const metadata = {
  title: "Buy Snackbar",
  description:
    "Order Eat Rrite Snackbar. Enter delivery details and pay securely.",
};

export default function SnackbarCheckoutPage() {
  return (
    <SiteShell current="snackbar">
      <SnackbarCheckoutBanner />
      <section className="bg-[linear-gradient(180deg,#fffdf6_0%,#f8f5f0_48%,#fff8e8_100%)] py-10 pb-28 md:py-16">
        <div className="container-er grid items-start gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:gap-12">
          <SnackbarCheckoutProduct />
          <div className="rounded-[24px] border-2 border-gold bg-white p-4 shadow-[0_18px_50px_rgba(229,184,88,0.16)] min-[400px]:p-6 sm:p-8">
            <p className="mb-4 font-heading text-xl text-brand">Delivery details</p>
            <SnackbarCheckoutForm />
          </div>
        </div>
      </section>
    </SiteShell>
  );
}
