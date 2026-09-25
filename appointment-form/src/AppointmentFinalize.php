<?php

declare(strict_types=1);

final class AppointmentFinalize
{
    public function __construct(
        private SlotService $slots,
        private GoogleAppsScriptClient $meet,
        private BookingStore $bookings
    ) {
    }

    public function complete(string $paymentId, array $booking, array $record): array
    {
        $result = $this->meet->createMeet([
            'name' => $booking['name'],
            'service' => $booking['service'],
            'phone' => $booking['phone'],
            'payment_id' => $paymentId,
            'start_iso' => $this->slots->slotStart($booking['date'], $booking['time'])->format(DateTimeInterface::ATOM),
            'end_iso' => $this->slots->slotEnd($booking['date'], $booking['time'])->format(DateTimeInterface::ATOM),
        ]);

        $completed = $this->bookings->update($paymentId, static function (array $row) use ($result): array {
            $row['status'] = 'completed';
            $row['meet_link'] = $result['meet_link'];
            $row['booked_at'] = $result['booked_at'];
            $row['completed_at'] = time();
            return $row;
        });

        $emailsSent = $this->sendEmails($booking, $paymentId, $result);
        if ($emailsSent) {
            $completed = $this->bookings->update($paymentId, static function (array $row): array {
                $row['emails_sent'] = true;
                return $row;
            });
        } else {
            $completed['email_error'] = 'confirmation emails could not be sent';
        }
        $completed['emails_sent'] = $emailsSent;

        return AppointmentResponses::finalized($completed);
    }

    /** @param array<string, mixed> $record */
    public function notifyQuestionnaire(array $record): void
    {
        try {
            require_once dirname(__DIR__, 2) . '/email/AppointmentEmails.php';
            $id = (string) ($record['id'] ?? '');
            send_customer_questionnaire_email([
                'name' => (string) ($record['name'] ?? ''),
                'email' => (string) ($record['email'] ?? ''),
                'service' => (string) ($record['service'] ?? ''),
                'display_date' => (string) ($record['display_date'] ?? ''),
                'display_time' => (string) ($record['display_time'] ?? ''),
                'questionnaire_url' => appointment_public_url(
                    'appointment-confirmed.php?appointmentId=' . rawurlencode($id)
                ),
            ]);
        } catch (Throwable $e) {
            error_log('Questionnaire invitation email failed: ' . $e->getMessage());
        }
    }

    private function sendEmails(array $booking, string $paymentId, array $result): bool
    {
        try {
            require_once dirname(__DIR__, 2) . '/email/AppointmentEmails.php';
            send_paid_booking_emails([
                'name' => $booking['name'],
                'email' => $booking['email'],
                'phone' => $booking['phone'],
                'service' => $booking['service'],
                'display_date' => $this->slots->displayDate($booking['date']),
                'display_time' => $this->slots->displayTime($booking['time']),
                'meet_link' => $result['meet_link'],
                'payment_id' => $paymentId,
                'booked_at' => $result['booked_at'],
            ]);
            return true;
        } catch (Throwable $e) {
            error_log('Appointment booking emails failed: ' . $e->getMessage());
            return false;
        }
    }
}
