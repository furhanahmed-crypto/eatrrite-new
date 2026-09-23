<?php

declare(strict_types=1);

final class SnackbarService
{
    private SnackbarRazorpayService $razorpay;
    private SnackbarOrderStore $orders;

    public function __construct(private array $config)
    {
        $this->razorpay = new SnackbarRazorpayService($config);
        $this->orders = new SnackbarOrderStore();
    }

    public function createOrder(array $input): array
    {
        $order = $this->validated($input);
        $razorpay = $this->razorpay->createOrder($order['quantity'], [
            'name' => $order['name'],
            'email' => $order['email'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'quantity' => (string) $order['quantity'],
            'product' => $this->config['product_name'],
        ]);

        return [
            'order_id' => $razorpay['id'],
            'amount' => $razorpay['amount'],
            'currency' => $razorpay['currency'],
            'key_id' => $this->razorpay->keyId(),
            'quantity' => $order['quantity'],
            'unit_rupees' => $this->razorpay->unitRupees(),
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

        $existing = $this->orders->findByPaymentId($paymentId);
        if ($existing) {
            return $existing + ['redirect' => 'thank-you.php'];
        }

        $order = $this->validated($details);
        $record = [
            'id' => 'sb_' . bin2hex(random_bytes(6)),
            'name' => $order['name'],
            'email' => $order['email'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'quantity' => $order['quantity'],
            'amount_rupees' => $this->razorpay->unitRupees() * $order['quantity'],
            'payment_id' => $paymentId,
            'order_id' => $orderId,
            'status' => 'paid',
            'created_at' => date('c'),
        ];

        $this->orders->save($record);
        return $record + ['redirect' => 'thank-you.php'];
    }

    /** @return array{name:string,email:string,phone:string,address:string,quantity:int} */
    private function validated(array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $phone = preg_replace('/\D+/', '', (string) ($input['phone'] ?? $input['mobilenumber'] ?? '')) ?? '';
        $address = trim((string) ($input['address'] ?? ''));
        $quantity = (int) ($input['quantity'] ?? 0);
        $min = (int) $this->config['min_quantity'];
        $max = (int) $this->config['max_quantity'];

        if ($name === '' || $email === '' || $phone === '' || $address === '') {
            throw new InvalidArgumentException('Please fill name, email, mobile number and address.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please enter a valid email.');
        }
        if (strlen($phone) < 10) {
            throw new InvalidArgumentException('Please enter a valid mobile number.');
        }
        if ($quantity < $min || $quantity > $max) {
            throw new InvalidArgumentException('Choose between ' . $min . ' and ' . $max . ' bars.');
        }

        return compact('name', 'email', 'phone', 'address', 'quantity');
    }
}
