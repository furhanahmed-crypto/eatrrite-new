export function getServerEnv() {
  return {
    databaseUrl: process.env.DATABASE_URL || "",
    razorpayKeyId: process.env.RAZORPAY_KEY_ID || "",
    razorpayKeySecret: process.env.RAZORPAY_KEY_SECRET || "",
    appsScriptUrl: (process.env.APPS_SCRIPT_URL || "").replace(/\/$/, ""),
    appsScriptSecret: process.env.APPS_SCRIPT_SECRET || "",
    adminDashboardPassword: process.env.ADMIN_DASHBOARD_PASSWORD || "",
    mailHost: process.env.MAIL_HOST || "",
    mailPort: Number(process.env.MAIL_PORT || 587),
    mailEncryption: process.env.MAIL_ENCRYPTION || "tls",
    mailUsername: process.env.MAIL_USERNAME || "",
    mailPassword: process.env.MAIL_PASSWORD || "",
    mailFromEmail: process.env.MAIL_FROM_EMAIL || "",
    mailFromName: process.env.MAIL_FROM_NAME || "Eat Rrite",
    mailAdminEmail: process.env.MAIL_ADMIN_EMAIL || "",
    mailAdminName: process.env.MAIL_ADMIN_NAME || "Eat Rrite Team",
  };
}

export function getPublicEnv() {
  return {
    razorpayKeyId: process.env.NEXT_PUBLIC_RAZORPAY_KEY_ID || "",
  };
}
