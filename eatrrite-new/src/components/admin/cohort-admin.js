export function formatCohortMonth(value) {
  const [year, month] = String(value || "").split("-");
  if (!year || !month) return value || "—";
  return new Date(Number(year), Number(month) - 1, 1).toLocaleString("en-IN", {
    month: "long",
    year: "numeric",
  });
}

export function formatAppliedAt(value) {
  if (!value) return "—";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return "—";
  return date.toLocaleString("en-IN", {
    day: "numeric",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

export async function fetchCohortApplications() {
  const res = await fetch("/api/admin/cohort-applications");
  const data = await res.json();
  return { res, data };
}

export async function deleteCohortApplication(id) {
  const res = await fetch("/api/admin/delete-cohort-application", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id }),
  });
  const data = await res.json();
  if (!data.ok) throw new Error(data.error || "Could not remove application");
  return data;
}
