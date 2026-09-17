import { redirect } from "next/navigation";
import { isAdminAuthed } from "@/lib/admin-auth";
import { AdminLoginForm } from "@/components/admin/AdminLoginForm";

export const metadata = {
  title: "Admin sign in",
  robots: { index: false, follow: false },
};

export default async function AdminLoginPage() {
  if (await isAdminAuthed()) {
    redirect("/admin/appointments");
  }

  return (
    <main className="flex min-h-screen items-center bg-cream px-4 py-16">
      <AdminLoginForm />
    </main>
  );
}
