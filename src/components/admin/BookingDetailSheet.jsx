"use client";

import { useEffect, useState } from "react";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/shared/ui/sheet";
import { Button } from "@/shared/ui/button";
import { ConfirmationModal } from "@/shared/components/ConfirmationModal";
import { formatDisplayTime, meetingEnd } from "@/lib/calendar";

export function BookingDetailSheet({
  open,
  onOpenChange,
  booking,
  onCancelBooking,
}) {
  const [current, setCurrent] = useState(booking);
  const [confirmOpen, setConfirmOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    if (booking) setCurrent(booking);
  }, [booking]);

  useEffect(() => {
    if (!open) {
      setConfirmOpen(false);
      setError("");
      setLoading(false);
    }
  }, [open]);

  async function handleConfirmCancel() {
    if (!current || !onCancelBooking) return;
    setLoading(true);
    setError("");
    try {
      await onCancelBooking(current);
      setConfirmOpen(false);
      onOpenChange(false);
    } catch (err) {
      setError(err.message || "Could not cancel this booking.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <>
      <Sheet open={open} onOpenChange={onOpenChange}>
        <SheetContent className="sm:max-w-md">
          <SheetHeader>
            <SheetTitle>{current?.name || "Client"}</SheetTitle>
            <SheetDescription>
              {current?.service || "Consultation"}
            </SheetDescription>
          </SheetHeader>
          {current ? (
            <div className="space-y-4 px-4 pb-4 text-sm">
              <p>
                <span className="text-soft">Date</span>
                <br />
                {current.date}
              </p>
              <p>
                <span className="text-soft">Meeting</span>
                <br />
                {formatDisplayTime(current.time)} –{" "}
                {formatDisplayTime(meetingEnd(current.time))}
              </p>
              <p>
                <span className="text-soft">Phone</span>
                <br />
                {current.phone || "—"}
              </p>
              <p>
                <span className="text-soft">Google Meet</span>
                <br />
                {current.meet_link ? (
                  <a
                    href={current.meet_link}
                    target="_blank"
                    rel="noreferrer"
                    className="break-all text-brand underline"
                  >
                    {current.meet_link}
                  </a>
                ) : (
                  "Meet link not stored on this row."
                )}
              </p>
              {error ? <p className="text-destructive">{error}</p> : null}
            </div>
          ) : null}
          {current && onCancelBooking ? (
            <SheetFooter>
              <Button
                type="button"
                variant="destructive"
                className="w-full"
                onClick={() => setConfirmOpen(true)}
              >
                Cancel booking
              </Button>
            </SheetFooter>
          ) : null}
        </SheetContent>
      </Sheet>

      <ConfirmationModal
        open={confirmOpen}
        onOpenChange={setConfirmOpen}
        title="Cancel this booking?"
        description="This removes the appointment from the sheet and frees the slot. The Meet event is cleared when possible."
        confirmLabel="Cancel booking"
        cancelLabel="Keep booking"
        confirmVariant="destructive"
        loading={loading}
        onConfirm={handleConfirmCancel}
      />
    </>
  );
}
