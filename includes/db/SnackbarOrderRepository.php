<?php

declare(strict_types=1);

final class SnackbarOrderRepository
{
    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        $stmt = app_pdo()->query(
            'SELECT * FROM snackbar_orders ORDER BY created_at DESC, id DESC'
        );
        return $stmt ? $stmt->fetchAll() : [];
    }

    /** @return array<string, mixed>|null */
    public function findByPaymentId(string $paymentId): ?array
    {
        if ($paymentId === '') {
            return null;
        }
        $stmt = app_pdo()->prepare(
            'SELECT * FROM snackbar_orders WHERE payment_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $paymentId]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** @param array<string, mixed> $order */
    public function save(array $order): array
    {
        app_pdo()->prepare(
            'INSERT INTO snackbar_orders (
                id, name, email, phone, address, quantity, amount_rupees,
                payment_id, order_id, status
            ) VALUES (
                :id, :name, :email, :phone, :address, :quantity, :amount_rupees,
                :payment_id, :order_id, :status
            )'
        )->execute([
            'id' => $order['id'] ?? app_id('sb_'),
            'name' => $order['name'],
            'email' => $order['email'],
            'phone' => $order['phone'],
            'address' => $order['address'],
            'quantity' => (int) $order['quantity'],
            'amount_rupees' => (int) $order['amount_rupees'],
            'payment_id' => $order['payment_id'] ?? '',
            'order_id' => $order['order_id'] ?? '',
            'status' => $order['status'] ?? 'paid',
        ]);

        return $order;
    }
}
