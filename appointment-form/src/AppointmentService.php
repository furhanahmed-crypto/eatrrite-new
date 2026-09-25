<?php

declare(strict_types=1);

final class AppointmentService
{
    private array $config;
    private SlotService $slots;
    private HoldService $holds;
    private RazorpayService $razorpay;
    private AppointmentValidator $validator;
    private AppointmentPayment $payment;
    private AppointmentCheckout $checkout;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->slots = new SlotService($config);
        $this->holds = new HoldService($config, $this->slots);
        $this->razorpay = new RazorpayService($config);
        $bookings = new BookingStore();
        $this->validator = new AppointmentValidator($config, $this->slots);
        $this->payment = new AppointmentPayment(
            $this->razorpay,
            $this->validator,
            $this->holds,
            $bookings,
            $this->slots
        );
        $this->checkout = new AppointmentCheckout(
            $this->payment,
            $bookings,
            $this->holds,
            $this->slots,
            new AppointmentFinalize($this->slots, new GoogleAppsScriptClient($config), $bookings)
        );
    }

    public function availability(): array
    {
        $occupied = $this->payment->occupancy();
        $from = $this->slots->today()->setTime(0, 0, 0);
        $to = $this->slots->lastBookableDate();
        $days = [];
        for ($day = $from; $day <= $to; $day = $day->modify('+1 day')) {
            $date = $day->format('Y-m-d');
            $days[$date] = $this->slots->availableTimesForDate($date, $occupied);
        }

        return [
            'timezone' => $this->config['timezone'],
            'days' => $days,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'customer_meeting_minutes' => $this->slots->customerMeetingMinutes(),
            'consultant_block_minutes' => $this->slots->consultantBlockMinutes(),
        ];
    }

    public function createOrder(array $input): array
    {
        $booking = $this->validator->validatedBooking($input);
        $this->slots->assertBookable($booking['date'], $booking['time'], $this->payment->occupancy());
        $order = $this->razorpay->createOrder([
            'name' => $booking['name'],
            'service' => $booking['service'],
            'phone' => $booking['phone'],
            'email' => $booking['email'],
            'date' => $booking['date'],
            'time' => $booking['time'],
        ]);
        $this->holds->hold($booking['date'], $booking['time'], $order['id']);

        return [
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency'],
            'key_id' => $this->razorpay->keyId(),
            'display_date' => $this->slots->displayDate($booking['date']),
            'display_time' => $this->slots->displayTime($booking['time']),
        ];
    }

    public function verifyPayment(array $input): array
    {
        return $this->checkout->verify($input);
    }

    public function finalizeBooking(array $input): array
    {
        return $this->checkout->finalize($input);
    }

    public function generateMeet(string $appointmentId): array
    {
        return $this->checkout->generateMeet($appointmentId);
    }

    public function validatedBooking(array $input): array
    {
        return $this->validator->validatedBooking($input);
    }
}
