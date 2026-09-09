<?php

declare(strict_types=1);

final class HoldService
{
    private string $file;
    private int $holdMinutes;
    private SlotService $slots;

    public function __construct(array $config, SlotService $slots)
    {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create appointment data directory.');
        }

        $this->file = $dir . DIRECTORY_SEPARATOR . 'holds.json';
        $this->holdMinutes = (int) $config['hold_minutes'];
        $this->slots = $slots;
    }

    /**
     * @return list<array{date:string,time:string,order_id:string,expires_at:int}>
     */
    public function activeHolds(): array
    {
        return $this->mutate(static fn (array $holds): array => $holds);
    }

    public function hold(string $date, string $time, string $orderId): void
    {
        $this->mutate(function (array $holds) use ($date, $time, $orderId): array {
            foreach ($holds as $row) {
                if ($row['date'] === $date && $this->slots->timesOverlap($row['time'], $time)) {
                    throw new InvalidArgumentException('That slot is on hold for another booking. Try a different time.');
                }
            }

            $holds[] = [
                'date' => $date,
                'time' => $time,
                'order_id' => $orderId,
                'expires_at' => time() + ($this->holdMinutes * 60),
            ];

            return $holds;
        });
    }

    public function releaseByOrder(string $orderId): void
    {
        $this->mutate(static function (array $holds) use ($orderId): array {
            return array_values(array_filter(
                $holds,
                static fn (array $row): bool => $row['order_id'] !== $orderId
            ));
        });
    }

    /**
     * @param callable(list<array{date:string,time:string,order_id:string,expires_at:int}>): list<array{date:string,time:string,order_id:string,expires_at:int}> $callback
     * @return list<array{date:string,time:string,order_id:string,expires_at:int}>
     */
    private function mutate(callable $callback): array
    {
        $handle = fopen($this->file, 'c+');
        if ($handle === false) {
            throw new RuntimeException('Unable to open slot hold file.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock slot hold file.');
            }

            $raw = stream_get_contents($handle);
            $holds = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);
            if (!is_array($holds)) {
                $holds = [];
            }

            $holds = $callback($this->prune($holds));

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, json_encode(array_values($holds), JSON_UNESCAPED_SLASHES));
            fflush($handle);

            return $holds;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * @param list<array> $holds
     * @return list<array{date:string,time:string,order_id:string,expires_at:int}>
     */
    private function prune(array $holds): array
    {
        $now = time();
        $kept = [];

        foreach ($holds as $row) {
            if (!is_array($row) || empty($row['expires_at']) || (int) $row['expires_at'] <= $now) {
                continue;
            }

            $kept[] = [
                'date' => (string) ($row['date'] ?? ''),
                'time' => (string) ($row['time'] ?? ''),
                'order_id' => (string) ($row['order_id'] ?? ''),
                'expires_at' => (int) $row['expires_at'],
            ];
        }

        return $kept;
    }
}
