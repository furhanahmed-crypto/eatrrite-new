<?php

declare(strict_types=1);

final class BookingStore
{
    private string $file;

    public function __construct()
    {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create appointment data directory.');
        }

        $this->file = $dir . DIRECTORY_SEPARATOR . 'bookings.json';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByPaymentId(string $paymentId): ?array
    {
        foreach ($this->readAll() as $row) {
            if (($row['payment_id'] ?? '') === $paymentId) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return 'completed'|'processing'|'claimed'
     */
    public function claimForFinalize(string $paymentId): string
    {
        $result = 'processing';

        $this->mutate(function (array $rows) use ($paymentId, &$result): array {
            foreach ($rows as $index => $row) {
                if (($row['payment_id'] ?? '') !== $paymentId) {
                    continue;
                }

                $status = (string) ($row['status'] ?? '');

                if ($status === 'completed' && !empty($row['meet_link'])) {
                    $result = 'completed';
                    return $rows;
                }

                if ($status === 'finalizing') {
                    $result = 'processing';
                    return $rows;
                }

                if ($status === 'verified' || $status === 'failed') {
                    $rows[$index]['status'] = 'finalizing';
                    unset($rows[$index]['error']);
                    $result = 'claimed';
                }

                return $rows;
            }

            throw new RuntimeException('Booking record not found.');
        });

        return $result;
    }

    /**
     * @param array<string, mixed> $row
     */
    public function saveVerified(array $row): void
    {
        $this->mutate(function (array $rows) use ($row): array {
            foreach ($rows as $existing) {
                if (($existing['payment_id'] ?? '') === $row['payment_id']) {
                    return $rows;
                }
            }

            $rows[] = $row;

            return $rows;
        });
    }

    /**
     * @param callable(array<string, mixed>): array<string, mixed> $callback
     * @return array<string, mixed>
     */
    public function update(string $paymentId, callable $callback): array
    {
        $updated = [];

        $this->mutate(function (array $rows) use ($paymentId, $callback, &$updated): array {
            foreach ($rows as $index => $row) {
                if (($row['payment_id'] ?? '') !== $paymentId) {
                    continue;
                }

                $rows[$index] = $callback($row);
                $updated = $rows[$index];

                return $rows;
            }

            throw new RuntimeException('Booking record not found.');
        });

        return $updated;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function readRow(string $paymentId): ?array
    {
        return $this->findByPaymentId($paymentId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readAll(): array
    {
        if (!is_readable($this->file)) {
            return [];
        }

        $raw = file_get_contents($this->file);
        $rows = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);

        return is_array($rows) ? $rows : [];
    }

    /**
     * @param callable(list<array<string, mixed>>): list<array<string, mixed>> $callback
     */
    private function mutate(callable $callback): void
    {
        $handle = fopen($this->file, 'c+');
        if ($handle === false) {
            throw new RuntimeException('Unable to open booking store.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock booking store.');
            }

            $raw = stream_get_contents($handle);
            $rows = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);
            if (!is_array($rows)) {
                $rows = [];
            }

            $rows = $callback($rows);

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, json_encode(array_values($rows), JSON_UNESCAPED_SLASHES));
            fflush($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
