<?php

declare(strict_types=1);

final class DisabledSlotRepository
{
    /** @return list<array{date:string,time:string}> */
    public function all(): array
    {
        $stmt = app_pdo()->query('SELECT date, time FROM disabled_slots ORDER BY date, time');
        return $stmt->fetchAll();
    }

    public function set(string $date, string $time, bool $hidden): void
    {
        if (!$hidden) {
            app_pdo()->prepare(
                'DELETE FROM disabled_slots WHERE date = :date AND time = :time'
            )->execute(['date' => $date, 'time' => $time]);
            return;
        }

        app_pdo()->prepare(
            'INSERT IGNORE INTO disabled_slots (id, date, time) VALUES (:id, :date, :time)'
        )->execute([
            'id' => app_id(),
            'date' => $date,
            'time' => $time,
        ]);
    }
}
