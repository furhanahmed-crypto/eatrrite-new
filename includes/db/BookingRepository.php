<?php

declare(strict_types=1);

final class BookingRepository
{
    private BookingWriter $writer;

    public function __construct()
    {
        $this->writer = new BookingWriter();
    }

    /** @return array<string, mixed>|null */
    public function findById(string $id): ?array
    {
        $stmt = app_pdo()->prepare('SELECT * FROM appointments WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        $row['emails_sent'] = !empty($row['emails_sent']);
        $row['questionnaire_completed'] = !empty($row['questionnaire_completed']);
        return $row;
    }

    /** @return array<string, mixed>|null */
    public function findByPaymentId(string $paymentId): ?array
    {
        $stmt = app_pdo()->prepare(
            'SELECT * FROM appointments WHERE payment_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $paymentId]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        $row['emails_sent'] = !empty($row['emails_sent']);
        $row['questionnaire_completed'] = !empty($row['questionnaire_completed']);
        return $row;
    }

    /** @return list<array<string, mixed>> */
    public function allCompleted(): array
    {
        $stmt = app_pdo()->query(
            "SELECT * FROM appointments
             WHERE status IN ('confirmed', 'verified', 'finalizing', 'completed')
             ORDER BY date, time"
        );
        $rows = [];
        foreach ($stmt->fetchAll() as $row) {
            $row['emails_sent'] = !empty($row['emails_sent']);
            $row['questionnaire_completed'] = !empty($row['questionnaire_completed']);
            $rows[] = $row;
        }
        return $rows;
    }

    /** @return list<array{date:string,time:string}> */
    public function occupiedSlots(): array
    {
        $stmt = app_pdo()->query(
            "SELECT date, time FROM appointments
             WHERE status IN ('confirmed', 'verified', 'finalizing', 'completed')"
        );
        return $stmt->fetchAll();
    }

    /** @param array<string, mixed> $row */
    public function saveVerified(array $row): void
    {
        if ($this->findByPaymentId((string) $row['payment_id']) !== null) {
            return;
        }

        app_pdo()->prepare(
            'INSERT INTO appointments (
                id, name, email, phone, service, date, time, meet_link,
                payment_id, order_id, status, booked_at, emails_sent,
                questionnaire_completed, display_date, display_time, verified_at
            ) VALUES (
                :id, :name, :email, :phone, :service, :date, :time, :meet_link,
                :payment_id, :order_id, :status, :booked_at, :emails_sent,
                :questionnaire_completed, :display_date, :display_time, :verified_at
            )'
        )->execute([
            'id' => app_id(),
            'name' => $row['name'],
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
            'service' => $row['service'] ?? '',
            'date' => $row['date'],
            'time' => $row['time'],
            'meet_link' => $row['meet_link'] ?? '',
            'payment_id' => $row['payment_id'],
            'order_id' => $row['order_id'] ?? '',
            'status' => $row['status'] ?? 'confirmed',
            'booked_at' => $row['booked_at'] ?? '',
            'emails_sent' => !empty($row['emails_sent']) ? 1 : 0,
            'questionnaire_completed' => !empty($row['questionnaire_completed']) ? 1 : 0,
            'display_date' => $row['display_date'] ?? '',
            'display_time' => $row['display_time'] ?? '',
            'verified_at' => $row['verified_at'] ?? time(),
        ]);
    }

    public function claimForFinalize(string $paymentId): string
    {
        return $this->writer->claimForFinalize($paymentId);
    }

    public function update(string $paymentId, callable $callback): array
    {
        return $this->writer->update($paymentId, $callback);
    }

    /** @return array<string, mixed> */
    public function deleteBySlot(string $date, string $time, string $phone, string $name): array
    {
        return $this->writer->deleteBySlot($date, $time, $phone, $name);
    }
}
