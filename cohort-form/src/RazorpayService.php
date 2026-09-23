<?php

declare(strict_types=1);

final class CohortRazorpayService
{
    public function __construct(private array $config)
    {
    }

    public function keyId(): string
    {
        return (string) $this->config['razorpay_key_id'];
    }

    public function consultationRupees(): int
    {
        return (int) $this->config['consultation_rupees'];
    }

    public function createOrder(array $notes): array
    {
        $rupees = $this->consultationRupees();
        if ($rupees < 1) {
            throw new RuntimeException('Order amount must be at least ₹1.');
        }

        $amountPaise = $rupees * 100;
        $response = $this->request('POST', 'https://api.razorpay.com/v1/orders', [
            'amount' => $amountPaise,
            'currency' => $this->config['currency'],
            'receipt' => 'co_' . bin2hex(random_bytes(6)),
            'payment_capture' => 1,
            'notes' => $notes,
        ]);

        if (empty($response['id'])) {
            throw new RuntimeException('Razorpay did not return an order id.');
        }

        return [
            'id' => (string) $response['id'],
            'amount' => (int) ($response['amount'] ?? $amountPaise),
            'currency' => (string) ($response['currency'] ?? $this->config['currency']),
        ];
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): void
    {
        $expected = hash_hmac(
            'sha256',
            $orderId . '|' . $paymentId,
            (string) $this->config['razorpay_key_secret']
        );
        if (!hash_equals($expected, $signature)) {
            throw new InvalidArgumentException('Payment signature mismatch.');
        }
    }

    private function request(string $method, string $url, ?array $body = null): array
    {
        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('Unable to start Razorpay request.');
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->keyId() . ':' . $this->config['razorpay_key_secret'],
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30,
        ];
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($body, JSON_UNESCAPED_SLASHES);
        }

        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        unset($ch);

        $decoded = is_string($raw) ? json_decode($raw, true) : null;
        if (!is_array($decoded)) {
            throw new RuntimeException('Invalid Razorpay response.');
        }
        if ($status >= 400) {
            throw new RuntimeException((string) ($decoded['error']['description'] ?? 'Razorpay request failed.'));
        }
        return $decoded;
    }
}
