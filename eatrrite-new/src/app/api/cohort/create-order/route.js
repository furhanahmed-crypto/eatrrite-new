import { createRazorpayOrder } from "@/lib/razorpay";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { getServerEnv } from "@/config/env";
import { siteConfig } from "@/config/site";
import { currentCohortMonth, validateCohortApplicant } from "@/config/cohort";
import { countPaidCohortApplications } from "@/lib/db/cohort-applications";

export const dynamic = "force-dynamic";

export async function POST(request) {
  try {
    const month = currentCohortMonth();
    const taken = await countPaidCohortApplications(month);
    if (taken >= siteConfig.cohortSpots) {
      return jsonFail(new Error("This month's cohort is full."), 409);
    }

    const applicant = validateCohortApplicant(await request.json());
    const amountRupees = siteConfig.cohortConsultationRupees;
    const order = await createRazorpayOrder(
      { ...applicant, product: "Cohort consultation", cohort_month: month },
      amountRupees
    );

    return jsonOk({
      order_id: order.id,
      amount: order.amount,
      currency: order.currency,
      key_id: getServerEnv().razorpayKeyId,
      amount_rupees: amountRupees,
    });
  } catch (error) {
    const status = /fill|valid/i.test(error.message || "") ? 400 : 500;
    return jsonFail(error, status);
  }
}
