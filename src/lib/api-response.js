import { NextResponse } from "next/server";

export function jsonOk(payload, status = 200) {
  return NextResponse.json({ ok: true, ...payload }, { status });
}

export function jsonFail(error, status = 500) {
  const message = error?.message || "A technical issue occurred. Please retry.";
  console.error("[appointment]", message, error);
  const publicMessage =
    status >= 500 || /apps script|razorpay|configured|prisma|database|neon/i.test(message)
      ? "A technical issue occurred. Please retry."
      : message;
  return NextResponse.json({ ok: false, error: publicMessage }, { status });
}
