"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { ConfirmationModal } from "@/shared/components/ConfirmationModal";
import { CohortApplicationCard } from "@/components/admin/CohortApplicationCard";
import {
  deleteCohortApplication,
  fetchCohortApplications,
} from "@/components/admin/cohort-admin";

export function CohortApplicationsList() {
  const router = useRouter();
  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [pending, setPending] = useState(null);
  const [removing, setRemoving] = useState(false);

  useEffect(() => {
    let alive = true;

    async function load() {
      try {
        const { res, data } = await fetchCohortApplications();
        if (!alive) return;
        if (res.status === 401) {
          router.replace("/admin/login");
          return;
        }
        if (!data.ok) throw new Error(data.error || "Failed to load");
        setRows(data.applications || []);
      } catch (err) {
        if (alive) setError("A technical issue occurred. Please retry.");
      } finally {
        if (alive) setLoading(false);
      }
    }

    void load();
    return () => {
      alive = false;
    };
  }, [router]);

  async function handleRemove() {
    if (!pending) return;
    setRemoving(true);
    try {
      await deleteCohortApplication(pending.id);
      setRows((current) => current.filter((row) => row.id !== pending.id));
      setPending(null);
    } catch (err) {
      setError(err.message || "Could not remove this application.");
    } finally {
      setRemoving(false);
    }
  }

  if (loading) {
    return <p className="text-soft">Loading applications…</p>;
  }

  return (
    <div className="space-y-5">
      <div>
        <h1 className="font-heading text-2xl leading-tight min-[400px]:text-3xl">
          Cohort applications
        </h1>
        <p className="mt-1 text-sm text-body">
          {rows.length} {rows.length === 1 ? "application" : "applications"} received.
        </p>
      </div>
      {error ? <p className="text-destructive">{error}</p> : null}
      {rows.length === 0 ? (
        <div className="rounded-[20px] border border-border-soft bg-surface px-5 py-10 text-center text-body">
          No cohort applications yet.
        </div>
      ) : (
        <div className="grid gap-4">
          {rows.map((application) => (
            <CohortApplicationCard
              key={application.id}
              application={application}
              onRemove={setPending}
            />
          ))}
        </div>
      )}
      <ConfirmationModal
        open={Boolean(pending)}
        onOpenChange={(open) => !open && !removing && setPending(null)}
        title="Remove this application?"
        description="This deletes the record from the list. It does not refund the consultation fee."
        confirmLabel="Remove application"
        cancelLabel="Keep"
        confirmVariant="destructive"
        loading={removing}
        onConfirm={handleRemove}
      />
    </div>
  );
}
