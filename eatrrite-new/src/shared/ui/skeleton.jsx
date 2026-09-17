import { cn } from "@/lib/utils";

export function Skeleton({ className }) {
  return (
    <div
      className={cn("animate-pulse rounded-md bg-soft/20 dark:bg-soft/30", className)}
    />
  );
}
