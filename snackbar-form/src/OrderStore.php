<?php

declare(strict_types=1);

final class SnackbarOrderStore
{
    private string $file;

    public function __construct()
    {
        $this->file = snackbar_storage_path('orders.json');
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

    public function save(array $order): array
    {
        $rows = $this->all();
        $rows[] = $order;
        file_put_contents(
            $this->file,
            json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
        return $order;
    }

    /** @return list<array<string, mixed>> */
    private function all(): array
    {
        $raw = file_get_contents($this->file);
        $data = json_decode($raw !== false ? $raw : '[]', true);
        return is_array($data) ? $data : [];
    }
}
