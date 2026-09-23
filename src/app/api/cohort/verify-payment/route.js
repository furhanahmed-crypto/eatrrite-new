import { verifyRazorpaySignature } from "@/lib/razorpay";
import {
  createCohortApplication,
  findCohortApplicationByPaymentId,
} from "@/lib/db/cohort-applications";
import { jsonFail, jsonOk } from "@/lib/api-response";
import { siteConfig } from "@/config/site";
import { currentCohortMonth, validateCohortApplicant } from "@/config/cohort";

export const dynamic = "force-dynamic";

export async function POST(request) {
  try {
    const body = await request.json();
    const orderId = String(body.razorpay_order_id || "");
    const paymentId = String(body.razorpay_payment_id || "");
    const signature = String(body.razorpay_signature || "");

    if (!verifyRazorpaySignature(orderId, paymentId, signature)) {
      return jsonFail(new Error("Payment verification failed."), 400);
    }

    const existing = await findCohortApplicationByPaymentId(paymentId);
    if (existing) {
      return jsonOk({ ...existing, redirect: "/cohort/thank-you" });
    }

    const applicant = validateCohortApplicant(body.order || {});
    const record = await createCohortApplication({
      ...applicant,
      amountRupees: siteConfig.cohortConsultationRupees,
      cohortMonth: currentCohortMonth(),
      paymentId,
      orderId,
      status: "paid",
    });

    return jsonOk({ ...record, redirect: "/cohort/thank-you" });
  } catch (error) {
    const status = /fill|valid|signature|verification/i.test(error.message || "")
      ? 400
      : 500;
    return jsonFail(error, status);
  }
}
