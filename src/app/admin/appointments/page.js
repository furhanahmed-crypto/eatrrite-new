import { redirect } from "next/navigation";
import { isAdminAuthed } from "@/lib/admin-auth";
import { AdminCalendar } from "@/components/admin/AdminCalendar";

export const metadata = {
  title: "Admin appointments",
  robots: { index: false, follow: false },
};

export default async function AdminAppointmentsPage() {
  if (!(await isAdminAuthed())) {
    redirect("/admin/login");
  }

  return (
    <main className="min-h-screen bg-cream px-4 py-10">
      <div className="container-er max-w-5xl">
        <AdminCalendar />
      </div>
    </main>
  );
}
