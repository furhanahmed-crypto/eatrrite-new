<?php

declare(strict_types=1);

final class CohortService
{
    private CohortRazorpayService $razorpay;
    private CohortApplicationStore $applications;

    public function __construct(private array $config)
    {
        $this->razorpay = new CohortRazorpayService($config);
        $this->applications = new CohortApplicationStore();
    }

    public function createOrder(array $input): array
    {
        $this->assertSpots();
        $applicant = $this->validated($input);
        $razorpay = $this->razorpay->createOrder([
            'name' => $applicant['name'],
            'email' => $applicant['email'],
            'phone' => $applicant['phone'],
            'product' => $this->config['product_name'],
            'cohort_month' => cohort_month(),
        ]);

        return [
            'order_id' => $razorpay['id'],
            'amount' => $razorpay['amount'],
            'currency' => $razorpay['currency'],
            'key_id' => $this->razorpay->keyId(),
            'amount_rupees' => $this->razorpay->consultationRupees(),
        ];
    }

    public function verifyPayment(array $input): array
    {
        $orderId = (string) ($input['razorpay_order_id'] ?? '');
        $paymentId = (string) ($input['razorpay_payment_id'] ?? '');
        $signature = (string) ($input['razorpay_signature'] ?? '');
        $details = is_array($input['order'] ?? null) ? $input['order'] : [];

        if ($orderId === '' || $paymentId === '' || $signature === '') {
            throw new InvalidArgumentException('Payment details are incomplete.');
        }

        $this->razorpay->verifySignature($orderId, $paymentId, $signature);

        $existing = $this->applications->findByPaymentId($paymentId);
        if ($existing) {
            return $existing + ['redirect' => 'thank-you.php'];
        }

        $applicant = $this->validated($details);
        $record = [
            'id' => 'co_' . bin2hex(random_bytes(6)),
            'name' => $applicant['name'],
            'email' => $applicant['email'],
            'phone' => $applicant['phone'],
            'amount_rupees' => $this->razorpay->consultationRupees(),
            'cohort_month' => cohort_month(),
            'payment_id' => $paymentId,
            'order_id' => $orderId,
            'status' => 'paid',
            'created_at' => date('c'),
        ];

        $this->applications->save($record);
        return $record + ['redirect' => 'thank-you.php'];
    }

    public function remainingSpots(): int
    {
        $taken = $this->applications->countPaidForMonth(cohort_month());
        return max(0, (int) $this->config['spots'] - $taken);
    }

    private function assertSpots(): void
    {
        if ($this->remainingSpots() <= 0) {
            throw new InvalidArgumentException("This month's cohort is full.");
        }
    }

    /** @return array{name:string,email:string,phone:string} */
    private function validated(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $phone = preg_replace('/\D+/', '', (string) ($input['phone'] ?? $input['mobilenumber'] ?? '')) ?? '';

        if ($name === '' || $email === '' || $phone === '') {
            throw new InvalidArgumentException('Please fill name, email and mobile number.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please enter a valid email.');
        }
        if (strlen($phone) < 10) {
            throw new InvalidArgumentException('Please enter a valid mobile number.');
        }

        return compact('name', 'email', 'phone');
    }
}
