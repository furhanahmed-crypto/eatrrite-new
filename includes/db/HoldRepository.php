<?php

declare(strict_types=1);

final class HoldRepository
{
    /** @return list<array{date:string,time:string,order_id:string,expires_at:int}> */
    public function active(): array
    {
        $this->prune();
        $stmt = app_pdo()->query(
            'SELECT date, time, order_id, UNIX_TIMESTAMP(expires_at) AS expires_at
             FROM holds WHERE expires_at > NOW()'
        );
        $rows = [];
        foreach ($stmt->fetchAll() as $row) {
            $rows[] = [
                'date' => (string) $row['date'],
                'time' => (string) $row['time'],
                'order_id' => (string) $row['order_id'],
                'expires_at' => (int) $row['expires_at'],
            ];
        }
        return $rows;
    }

    public function add(string $date, string $time, string $orderId, int $expiresAt): void
    {
        $this->prune();
        app_pdo()->prepare(
            'INSERT INTO holds (id, hold_id, date, time, order_id, expires_at)
             VALUES (:id, :hold_id, :date, :time, :order_id, FROM_UNIXTIME(:expires_at))'
        )->execute([
            'id' => app_id(),
            'hold_id' => $orderId,
            'date' => $date,
            'time' => $time,
            'order_id' => $orderId,
            'expires_at' => $expiresAt,
        ]);
    }

    public function releaseByOrder(string $orderId): void
    {
        app_pdo()->prepare('DELETE FROM holds WHERE order_id = :id')->execute([
            'id' => $orderId,
        ]);
    }

    private function prune(): void
    {
        app_pdo()->exec('DELETE FROM holds WHERE expires_at <= NOW()');
    }
}
