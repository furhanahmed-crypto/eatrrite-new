import { NextResponse } from "next/server";
import {
  ADMIN_COOKIE,
  adminSessionToken,
  passwordsMatch,
} from "@/lib/admin-auth";
import { jsonFail, jsonOk } from "@/lib/api-response";

export async function POST(request) {
  try {
    const body = await request.json();
    const password = String(body.password || "");
    if (!passwordsMatch(password)) {
      return jsonFail(new Error("Incorrect password."), 401);
    }

    const response = NextResponse.json({ ok: true });
    response.cookies.set(ADMIN_COOKIE, adminSessionToken(), {
      httpOnly: true,
      sameSite: "lax",
      path: "/",
      secure: process.env.NODE_ENV === "production",
      maxAge: 60 * 60 * 12,
    });
    return response;
  } catch (error) {
    return jsonFail(error, 500);
  }
}
