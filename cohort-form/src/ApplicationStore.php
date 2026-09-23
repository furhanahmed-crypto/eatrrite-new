<?php

declare(strict_types=1);

final class CohortApplicationStore
{
    private string $file;

    public function __construct()
    {
        $this->file = cohort_storage_path('applications.json');
        if (!is_file($this->file)) {
            file_put_contents($this->file, '[]', LOCK_EX);
        }
    }

    public function findByPaymentId(string $paymentId): ?array
    {
        if ($paymentId === '') {
            return null;
        }
        foreach ($this->all() as $row) {
            if (($row['payment_id'] ?? '') === $paymentId) {
                return $row;
            }
        }
        return null;
    }

    public function countPaidForMonth(string $month): int
    {
        $count = 0;
        foreach ($this->all() as $row) {
            if (($row['cohort_month'] ?? '') === $month && ($row['status'] ?? '') === 'paid') {
                $count++;
            }
        }
        return $count;
    }

    public function save(array $application): array
    {
        $rows = $this->all();
        $rows[] = $application;
        file_put_contents(
            $this->file,
            json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
        return $application;
    }

    /** @return list<array<string, mixed>> */
    private function all(): array
    {
        $raw = file_get_contents($this->file);
        $data = json_decode($raw !== false ? $raw : '[]', true);
        return is_array($data) ? $data : [];
    }
}
