import { Button } from "@/shared/ui/button";
import { formatAppliedAt, formatCohortMonth } from "@/components/admin/cohort-admin";

export function CohortApplicationCard({ application, onRemove }) {
  const fee = Number(application.amount_rupees || 0).toLocaleString("en-IN");

  return (
    <article className="rounded-[20px] border border-border-soft bg-surface p-4 shadow-er min-[400px]:p-5">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <div className="min-w-0">
          <h2 className="font-heading text-lg text-ink">{application.name}</h2>
          <p className="mt-1 text-sm text-body">
            <a href={`mailto:${application.email}`} className="text-brand">
              {application.email}
            </a>
            {application.phone ? (
              <>
                {" · "}
                <a href={`tel:${application.phone}`}>{application.phone}</a>
              </>
            ) : null}
          </p>
        </div>
        <span className="rounded-full bg-mint px-3 py-1 text-xs font-semibold tracking-wide text-brand uppercase">
          {application.status || "paid"}
        </span>
      </div>
      <dl className="mt-4 grid gap-3 text-sm sm:grid-cols-2">
        <div>
          <dt className="text-xs tracking-[0.04em] text-soft uppercase">Cohort</dt>
          <dd className="mt-0.5 font-medium text-ink">
            {formatCohortMonth(application.cohort_month)}
          </dd>
        </div>
        <div>
          <dt className="text-xs tracking-[0.04em] text-soft uppercase">Consultation</dt>
          <dd className="mt-0.5 font-medium text-ink">₹{fee}</dd>
        </div>
        <div>
          <dt className="text-xs tracking-[0.04em] text-soft uppercase">Applied</dt>
          <dd className="mt-0.5 text-body">{formatAppliedAt(application.created_at)}</dd>
        </div>
        <div>
          <dt className="text-xs tracking-[0.04em] text-soft uppercase">Payment</dt>
          <dd className="mt-0.5 break-all text-body">
            {application.payment_id || "—"}
          </dd>
        </div>
      </dl>
      <div className="mt-4 flex justify-end">
        <Button type="button" variant="destructive" onClick={() => onRemove(application)}>
          Remove
        </Button>
      </div>
    </article>
  );
}
