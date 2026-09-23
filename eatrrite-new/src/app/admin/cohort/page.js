import { redirect } from "next/navigation";
import { isAdminAuthed } from "@/lib/admin-auth";
import { AdminHeader } from "@/components/admin/AdminHeader";
import { CohortApplicationsList } from "@/components/admin/CohortApplicationsList";

export const metadata = {
  title: "Admin cohort applications",
  robots: { index: false, follow: false },
};

export default async function AdminCohortPage() {
  if (!(await isAdminAuthed())) {
    redirect("/admin/login");
  }

  return (
    <>
      <AdminHeader current="cohort" />
      <main className="min-h-screen overflow-x-hidden bg-cream py-6 min-[400px]:py-10">
        <div className="container-er max-w-5xl">
          <CohortApplicationsList />
        </div>
      </main>
    </>
  );
}
