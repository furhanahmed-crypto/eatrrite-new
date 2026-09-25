<?php

declare(strict_types=1);

final class SnackbarOrderStore
{
    private SnackbarOrderRepository $orders;

    public function __construct()
    {
        $this->orders = new SnackbarOrderRepository();
    }

    public function findByPaymentId(string $paymentId): ?array
    {
        return $this->orders->findByPaymentId($paymentId);
    }

    public function save(array $order): array
    {
        return $this->orders->save($order);
    }
}
