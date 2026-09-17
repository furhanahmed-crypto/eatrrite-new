import { Skeleton } from "@/shared/ui/skeleton";

export function AdminCalendarSkeleton() {
  const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  return (
    <div className="space-y-6" aria-busy="true" aria-label="Loading appointments">
      <div className="space-y-4">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="space-y-2">
            <Skeleton className="h-3 w-16" />
            <Skeleton className="h-9 w-48" />
          </div>
          <Skeleton className="h-9 w-24 rounded-lg" />
        </div>
        <div className="flex items-center justify-between">
          <Skeleton className="h-9 w-16 rounded-lg" />
          <Skeleton className="h-5 w-36" />
          <Skeleton className="h-9 w-16 rounded-lg" />
        </div>
      </div>

      <div>
        <div className="mb-2 grid grid-cols-7 gap-1 text-center text-xs text-soft">
          {weekdays.map((label) => (
            <div key={label}>{label}</div>
          ))}
        </div>
        <div className="grid grid-cols-7 gap-1">
          {Array.from({ length: 42 }).map((_, index) => (
            <div
              key={index}
              className="min-h-16 rounded-xl border border-border-soft p-2"
            >
              <Skeleton className="h-4 w-6" />
              {index % 7 === 2 || index % 7 === 4 ? (
                <Skeleton className="mt-2 h-3 w-14" />
              ) : null}
            </div>
          ))}
        </div>
      </div>

      <div>
        <Skeleton className="mb-3 h-6 w-40" />
        <div className="space-y-2">
          {Array.from({ length: 6 }).map((_, index) => (
            <div
              key={index}
              className="flex items-center justify-between gap-3 rounded-2xl border border-border-soft px-4 py-3"
            >
              <div className="space-y-2">
                <Skeleton className="h-4 w-20" />
                <Skeleton className="h-3 w-28" />
              </div>
              <Skeleton className="h-3 w-16" />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
