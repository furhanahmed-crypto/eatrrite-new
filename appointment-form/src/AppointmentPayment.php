<?php

declare(strict_types=1);

final class AppointmentPayment
{
    public function __construct(
        private RazorpayService $razorpay,
        private AppointmentValidator $validator,
        private HoldService $holds,
        private BookingStore $bookings,
        private SlotService $slots
    ) {
    }

    public function assertPaid(array $input): array
    {
        $orderId = trim((string) ($input['razorpay_order_id'] ?? ''));
        $paymentId = trim((string) ($input['razorpay_payment_id'] ?? ''));
        $signature = trim((string) ($input['razorpay_signature'] ?? ''));
        if ($orderId === '' || $paymentId === '' || $signature === '') {
            throw new InvalidArgumentException('Payment details are incomplete.');
        }

        $this->razorpay->verifySignature($orderId, $paymentId, $signature);
        $order = $this->razorpay->fetchOrder($orderId);
        $notes = is_array($order['notes'] ?? null) ? $order['notes'] : [];
        $booking = $this->validator->validatedBooking([
            'name' => $notes['name'] ?? '',
            'programname' => $notes['service'] ?? '',
            'mobilenumber' => $notes['phone'] ?? '',
            'email' => $notes['email'] ?? '',
            'date' => $notes['date'] ?? '',
            'time' => $notes['time'] ?? '',
        ]);
        if ((int) ($order['amount'] ?? 0) !== $this->razorpay->amountRupees() * 100) {
            throw new InvalidArgumentException('Paid amount does not match the appointment fee.');
        }

        return [$paymentId, $orderId, $booking];
    }

    /** @return list<array{date:string,time:string}> */
    public function occupancy(?string $ignoreOrderId = null): array
    {
        $holds = $this->holds->activeHolds();
        if ($ignoreOrderId !== null) {
            $holds = array_values(array_filter(
                $holds,
                static fn (array $row): bool => $row['order_id'] !== $ignoreOrderId
            ));
        }

        return $this->slots->occupancyRows($this->bookings->occupiedSlots(), $holds);
    }
}
