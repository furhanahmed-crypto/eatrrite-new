<?php

declare(strict_types=1);

final class AppointmentCheckout
{
    public function __construct(
        private AppointmentPayment $payment,
        private BookingStore $bookings,
        private HoldService $holds,
        private SlotService $slots,
        private AppointmentFinalize $finalize
    ) {
    }

    public function verify(array $input): array
    {
        [$paymentId, $orderId, $booking] = $this->payment->assertPaid($input);
        $existing = $this->bookings->findByPaymentId($paymentId);
        if ($existing !== null) {
            return AppointmentResponses::verified($existing);
        }

        $this->holds->releaseByOrder($orderId);
        $this->bookings->saveVerified([
            'payment_id' => $paymentId,
            'order_id' => $orderId,
            'status' => 'confirmed',
            'questionnaire_completed' => false,
            'name' => $booking['name'],
            'email' => $booking['email'],
            'phone' => $booking['phone'],
            'service' => $booking['service'],
            'date' => $booking['date'],
            'time' => $booking['time'],
            'display_date' => $this->slots->displayDate($booking['date']),
            'display_time' => $this->slots->displayTime($booking['time']),
            'meet_link' => '',
            'booked_at' => '',
            'emails_sent' => false,
            'verified_at' => time(),
        ]);

        $record = $this->bookings->findByPaymentId($paymentId) ?? [];
        $this->finalize->notifyQuestionnaire($record);
        return AppointmentResponses::verified($record);
    }

    public function finalize(array $input): array
    {
        [$paymentId, $orderId, $booking] = $this->payment->assertPaid($input);
        $record = $this->bookings->findByPaymentId($paymentId);
        if ($record === null || ($record['order_id'] ?? '') !== $orderId) {
            throw new InvalidArgumentException('Payment verified record not found.');
        }
        if (($record['status'] ?? '') === 'completed' && !empty($record['meet_link'])) {
            return AppointmentResponses::finalized($record);
        }
        if (empty($record['questionnaire_completed'])) {
            throw new InvalidArgumentException('Complete the questionnaire first.');
        }

        $claim = $this->bookings->claimForFinalize($paymentId);
        if ($claim === 'completed') {
            return AppointmentResponses::finalized($this->bookings->findByPaymentId($paymentId) ?? $record);
        }
        if ($claim === 'processing') {
            return ['status' => 'processing'];
        }

        try {
            $key = $this->slots->slotKey($booking['date'], $booking['time']);
            $occupied = array_values(array_filter(
                $this->payment->occupancy($orderId),
                fn (array $row): bool => $this->slots->slotKey($row['date'], $row['time']) !== $key
            ));
            $this->slots->assertBookable($booking['date'], $booking['time'], $occupied);
            return $this->finalize->complete($paymentId, $booking, $record);
        } catch (Throwable $e) {
            $this->bookings->update($paymentId, static function (array $row) use ($e): array {
                $row['status'] = 'failed';
                $row['error'] = $e->getMessage();
                return $row;
            });
            throw $e;
        }
    }

    public function generateMeet(string $appointmentId): array
    {
        $record = $this->bookings->findById($appointmentId);
        if ($record === null) {
            throw new InvalidArgumentException('Appointment not found.');
        }
        if (empty($record['questionnaire_completed'])) {
            throw new InvalidArgumentException('Complete the questionnaire first.');
        }
        if (($record['status'] ?? '') === 'completed' && !empty($record['meet_link'])) {
            return AppointmentResponses::finalized($record);
        }
        $paymentId = (string) $record['payment_id'];
        $claim = $this->bookings->claimForFinalize($paymentId);
        if ($claim === 'completed') {
            return AppointmentResponses::finalized($this->bookings->findById($appointmentId) ?? $record);
        }
        if ($claim === 'processing') {
            return ['status' => 'processing'];
        }
        try {
            return $this->finalize->complete($paymentId, [
                'name' => (string) $record['name'],
                'email' => (string) $record['email'],
                'phone' => (string) $record['phone'],
                'service' => (string) $record['service'],
                'date' => (string) $record['date'],
                'time' => (string) $record['time'],
            ], $record);
        } catch (Throwable $e) {
            $this->bookings->update($paymentId, static function (array $row) use ($e): array {
                $row['status'] = 'failed';
                $row['error'] = $e->getMessage();
                return $row;
            });
            throw $e;
        }
    }
}
