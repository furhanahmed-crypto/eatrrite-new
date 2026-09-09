<?php

declare(strict_types=1);

final class AppointmentService
{
    private array $config;
    private SlotService $slots;
    private HoldService $holds;
    private RazorpayService $razorpay;
    private GoogleAppsScriptClient $sheet;
    private BookingStore $bookings;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->slots = new SlotService($config);
        $this->holds = new HoldService($config, $this->slots);
        $this->razorpay = new RazorpayService($config);
        $this->sheet = new GoogleAppsScriptClient($config);
        $this->bookings = new BookingStore();
    }

    /**
     * @return array{
     *   timezone:string,
     *   days:array<string, list<string>>,
     *   from:string,
     *   to:string,
     *   customer_meeting_minutes:int,
     *   consultant_block_minutes:int
     * }
     */
    public function availability(): array
    {
        $occupied = $this->occupancy();
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

    /**
     * @return array{order_id:string,amount:int,currency:string,key_id:string}
     */
    public function createOrder(array $input): array
    {
        $booking = $this->validatedBooking($input);

        if (!$this->sheet->isConfigured()) {
            throw new RuntimeException('Online booking is not configured yet. Add the Google Apps Script web app URL to .env.');
        }

        $this->slots->assertBookable($booking['date'], $booking['time'], $this->occupancy());

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

    /**
     * Fast path after Razorpay success — verifies payment only, no Google Sheet / Meet yet.
     *
     * @return array{
     *   payment_id:string,
     *   order_id:string,
     *   name:string,
     *   email:string,
     *   service:string,
     *   display_date:string,
     *   display_time:string,
     *   meet_link_ready:bool,
     *   meet_link?:string,
     *   emails_sent?:bool
     * }
     */
    public function verifyPayment(array $input): array
    {
        [$paymentId, $orderId, $booking] = $this->assertPaidBooking($input);

        $existing = $this->bookings->findByPaymentId($paymentId);
        if ($existing !== null) {
            return $this->verifiedResponse($existing);
        }

        $this->holds->releaseByOrder($orderId);

        $this->bookings->saveVerified([
            'payment_id' => $paymentId,
            'order_id' => $orderId,
            'status' => 'verified',
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

        return $this->verifiedResponse($this->bookings->findByPaymentId($paymentId) ?? []);
    }

    /**
     * Slow path — sheet row, Google Meet link, confirmation emails.
     *
     * @return array{
     *   status:string,
     *   meet_link?:string,
     *   booked_at?:string,
     *   emails_sent?:bool,
     *   email_error?:string,
     *   error?:string
     * }
     */
    public function finalizeBooking(array $input): array
    {
        [$paymentId, $orderId, $booking] = $this->assertPaidBooking($input);

        $record = $this->bookings->findByPaymentId($paymentId);
        if ($record === null) {
            throw new InvalidArgumentException('Payment verified record not found. Refresh the page and contact us if this persists.');
        }

        if (($record['order_id'] ?? '') !== $orderId) {
            throw new InvalidArgumentException('Booking details do not match this payment.');
        }

        if (($record['status'] ?? '') === 'completed' && !empty($record['meet_link'])) {
            return $this->finalizedResponse($record);
        }

        $claim = $this->bookings->claimForFinalize($paymentId);
        if ($claim === 'completed') {
            $record = $this->bookings->findByPaymentId($paymentId) ?? $record;

            return $this->finalizedResponse($record);
        }

        if ($claim === 'processing') {
            return [
                'status' => 'processing',
            ];
        }

        try {
            $occupied = array_values(array_filter(
                $this->occupancy($orderId),
                fn (array $row): bool => $this->slots->slotKey($row['date'], $row['time']) !== $this->slots->slotKey($booking['date'], $booking['time'])
            ));
            $this->slots->assertBookable($booking['date'], $booking['time'], $occupied);

            $result = $this->sheet->book([
                'name' => $booking['name'],
                'service' => $booking['service'],
                'phone' => $booking['phone'],
                'date' => $booking['date'],
                'time' => $booking['time'],
                'payment_id' => $paymentId,
                'start_iso' => $this->slots->slotStart($booking['date'], $booking['time'])->format(DateTimeInterface::ATOM),
                'end_iso' => $this->slots->slotEnd($booking['date'], $booking['time'])->format(DateTimeInterface::ATOM),
                'consultant_block_minutes' => $this->slots->consultantBlockMinutes(),
            ]);

            $completed = $this->bookings->update($paymentId, static function (array $row) use ($result): array {
                $row['status'] = 'completed';
                $row['meet_link'] = $result['meet_link'];
                $row['booked_at'] = $result['booked_at'];
                $row['completed_at'] = time();

                return $row;
            });

            $emailsSent = false;
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
                $emailsSent = true;
                $completed = $this->bookings->update($paymentId, static function (array $row): array {
                    $row['emails_sent'] = true;

                    return $row;
                });
            } catch (Throwable $e) {
                error_log('Appointment booking emails failed: ' . $e->getMessage());
                $completed['email_error'] = 'confirmation emails could not be sent';
            }

            $completed['emails_sent'] = $emailsSent;

            return $this->finalizedResponse($completed);
        } catch (Throwable $e) {
            $this->bookings->update($paymentId, static function (array $row) use ($e): array {
                $row['status'] = 'failed';
                $row['error'] = $e->getMessage();

                return $row;
            });

            throw $e;
        }
    }

    /**
     * @return array{payment_id:string,order_id:string,booking:array{name:string,email:string,service:string,phone:string,date:string,time:string}}
     */
    private function assertPaidBooking(array $input): array
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

        $booking = $this->validatedBooking([
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

    /**
     * @param array<string, mixed> $record
     * @return array{
     *   payment_id:string,
     *   order_id:string,
     *   name:string,
     *   email:string,
     *   service:string,
     *   display_date:string,
     *   display_time:string,
     *   meet_link_ready:bool,
     *   meet_link?:string,
     *   emails_sent?:bool
     * }
     */
    private function verifiedResponse(array $record): array
    {
        $completed = ($record['status'] ?? '') === 'completed' && !empty($record['meet_link']);
        $response = [
            'payment_id' => (string) ($record['payment_id'] ?? ''),
            'order_id' => (string) ($record['order_id'] ?? ''),
            'name' => (string) ($record['name'] ?? ''),
            'email' => (string) ($record['email'] ?? ''),
            'service' => (string) ($record['service'] ?? ''),
            'display_date' => (string) ($record['display_date'] ?? ''),
            'display_time' => (string) ($record['display_time'] ?? ''),
            'meet_link_ready' => $completed,
        ];

        if ($completed) {
            $response['meet_link'] = (string) $record['meet_link'];
            $response['emails_sent'] = (bool) ($record['emails_sent'] ?? false);
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $record
     * @return array{
     *   status:string,
     *   meet_link?:string,
     *   booked_at?:string,
     *   emails_sent?:bool,
     *   email_error?:string
     * }
     */
    private function finalizedResponse(array $record): array
    {
        $response = [
            'status' => 'completed',
            'meet_link' => (string) ($record['meet_link'] ?? ''),
            'booked_at' => (string) ($record['booked_at'] ?? ''),
            'emails_sent' => (bool) ($record['emails_sent'] ?? false),
        ];

        if (!empty($record['email_error'])) {
            $response['email_error'] = (string) $record['email_error'];
        }

        return $response;
    }

    /**
     * @return array{name:string,email:string,service:string,phone:string,date:string,time:string}
     */
    public function validatedBooking(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $service = trim((string) ($input['programname'] ?? $input['service'] ?? ''));
        $phone = preg_replace('/\D+/', '', (string) ($input['mobilenumber'] ?? $input['phone'] ?? '')) ?? '';
        $date = trim((string) ($input['date'] ?? ''));
        $time = trim((string) ($input['time'] ?? ''));

        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }

        if ($name === '' || !preg_match('/^[\p{L}\s.\'-]{2,80}$/u', $name)) {
            throw new InvalidArgumentException('Enter a valid name.');
        }

        if (!in_array($service, $this->config['services'], true)) {
            throw new InvalidArgumentException('Select a service.');
        }

        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            throw new InvalidArgumentException('Enter a valid 10-digit mobile number.');
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }

        if ($date === '' || $time === '') {
            throw new InvalidArgumentException('Select an appointment date and time.');
        }

        $this->slots->parseDate($date);
        if (!$this->slots->isOfferedTime($date, $time)) {
            throw new InvalidArgumentException('Select a valid appointment time.');
        }

        return [
            'name' => $name,
            'email' => $email,
            'service' => $service,
            'phone' => $phone,
            'date' => $date,
            'time' => $time,
        ];
    }

    /**
     * @return list<array{date:string,time:string}>
     */
    private function occupancy(?string $ignoreOrderId = null): array
    {
        $holds = $this->holds->activeHolds();
        if ($ignoreOrderId !== null) {
            $holds = array_values(array_filter(
                $holds,
                static fn (array $row): bool => $row['order_id'] !== $ignoreOrderId
            ));
        }

        return $this->slots->occupancyRows($this->sheet->listBookedSlots(), $holds);
    }
}
