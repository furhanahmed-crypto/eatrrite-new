<?php

declare(strict_types=1);

final class CohortApplicationRepository
{
    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        $stmt = app_pdo()->query(
            'SELECT * FROM cohort_applications ORDER BY created_at DESC, id DESC'
        );
        return $stmt ? $stmt->fetchAll() : [];
    }

    public function delete(string $id): void
    {
        if ($id === '') {
            return;
        }
        app_pdo()->prepare('DELETE FROM cohort_applications WHERE id = :id')->execute(['id' => $id]);
    }

    /** @return array<string, mixed>|null */
    public function findByPaymentId(string $paymentId): ?array
    {
        if ($paymentId === '') {
            return null;
        }
        $stmt = app_pdo()->prepare(
            'SELECT * FROM cohort_applications WHERE payment_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $paymentId]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function countPaidForMonth(string $month): int
    {
        $stmt = app_pdo()->prepare(
            "SELECT COUNT(*) FROM cohort_applications
             WHERE cohort_month = :month AND status = 'paid'"
        );
        $stmt->execute(['month' => $month]);
        return (int) $stmt->fetchColumn();
    }

    /** @param array<string, mixed> $application */
    public function save(array $application): array
    {
        app_pdo()->prepare(
            'INSERT INTO cohort_applications (
                id, name, email, phone, amount_rupees, cohort_month,
                payment_id, order_id, status
            ) VALUES (
                :id, :name, :email, :phone, :amount_rupees, :cohort_month,
                :payment_id, :order_id, :status
            )'
        )->execute([
            'id' => $application['id'] ?? app_id('co_'),
            'name' => $application['name'],
            'email' => $application['email'],
            'phone' => $application['phone'],
            'amount_rupees' => (int) $application['amount_rupees'],
            'cohort_month' => $application['cohort_month'],
            'payment_id' => $application['payment_id'] ?? '',
            'order_id' => $application['order_id'] ?? '',
            'status' => $application['status'] ?? 'paid',
        ]);

        return $application;
    }
}
