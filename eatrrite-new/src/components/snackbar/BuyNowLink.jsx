import Link from "next/link";
import { siteConfig } from "@/config/site";
import { cn } from "@/lib/utils";

export function BuyNowLink({
  className,
  label = "Buy now",
  showPrice = true,
}) {
  const price = siteConfig.snackbarAmountRupees.toLocaleString("en-IN");

  return (
    <Link
      href="/snackbar/checkout"
      className={cn(
        "inline-flex h-11 items-center justify-center rounded-full bg-gold px-5 font-heading text-[15px] font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a] min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base",
        className
      )}
    >
      {showPrice ? `${label} · ₹${price}` : label}
    </Link>
  );
}
