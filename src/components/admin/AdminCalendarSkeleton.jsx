import { Skeleton } from "@/shared/ui/skeleton";

export function AdminCalendarSkeleton() {
  const weekdays = ["S", "M", "T", "W", "T", "F", "S"];

  return (
    <div
      className="space-y-5 min-[400px]:space-y-6"
      aria-busy="true"
      aria-label="Loading appointments"
    >
      <div className="space-y-4">
        <div className="flex items-start justify-between gap-3">
          <div className="space-y-2">
            <Skeleton className="h-3 w-16" />
            <Skeleton className="h-8 w-40 min-[400px]:h-9 min-[400px]:w-48" />
          </div>
          <Skeleton className="h-9 w-20 shrink-0 rounded-lg" />
        </div>
        <div className="flex items-center justify-between gap-2">
          <Skeleton className="h-8 w-14 rounded-lg" />
          <Skeleton className="h-5 w-28 min-[400px]:w-36" />
          <Skeleton className="h-8 w-14 rounded-lg" />
        </div>
      </div>

      <div className="min-w-0">
        <div className="mb-2 grid grid-cols-7 gap-0.5 text-center text-[10px] text-soft min-[400px]:gap-1">
          {weekdays.map((label, index) => (
            <div key={`${label}-${index}`}>{label}</div>
          ))}
        </div>
        <div className="grid grid-cols-7 gap-0.5 min-[400px]:gap-1">
          {Array.from({ length: 42 }).map((_, index) => (
            <div
              key={index}
              className="min-h-11 rounded-lg border border-border-soft p-1 min-[400px]:min-h-14 min-[400px]:rounded-xl min-[400px]:p-2"
            >
              <Skeleton className="h-3 w-4 min-[400px]:h-4 min-[400px]:w-6" />
            </div>
          ))}
        </div>
      </div>

      <div>
        <Skeleton className="mb-3 h-5 w-36" />
        <div className="space-y-2">
          {Array.from({ length: 6 }).map((_, index) => (
            <div
              key={index}
              className="flex items-center justify-between gap-2 rounded-2xl border border-border-soft px-3 py-3 min-[400px]:px-4"
            >
              <div className="space-y-2">
                <Skeleton className="h-4 w-20" />
                <Skeleton className="h-3 w-24" />
              </div>
              <Skeleton className="h-3 w-10" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
