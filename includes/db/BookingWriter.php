<?php

declare(strict_types=1);

final class BookingWriter
{
    /** @return 'completed'|'processing'|'claimed' */
    public function claimForFinalize(string $paymentId): string
    {
        $pdo = app_pdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'SELECT * FROM appointments WHERE payment_id = :id LIMIT 1 FOR UPDATE'
            );
            $stmt->execute(['id' => $paymentId]);
            $row = $stmt->fetch();
            if ($row === false) {
                throw new RuntimeException('Booking record not found.');
            }

            $status = (string) ($row['status'] ?? '');
            if ($status === 'completed' && ($row['meet_link'] ?? '') !== '') {
                $pdo->commit();
                return 'completed';
            }
            if ($status === 'finalizing') {
                $pdo->commit();
                return 'processing';
            }
            $ready = !empty($row['questionnaire_completed']);
            if ($ready && ($status === 'confirmed' || $status === 'verified' || $status === 'failed')) {
                $pdo->prepare(
                    "UPDATE appointments SET status = 'finalizing', error = NULL
                     WHERE payment_id = :id"
                )->execute(['id' => $paymentId]);
                $pdo->commit();
                return 'claimed';
            }

            $pdo->commit();
            return 'processing';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param callable(array<string, mixed>): array<string, mixed> $callback
     * @return array<string, mixed>
     */
    public function update(string $paymentId, callable $callback): array
    {
        $repo = new BookingRepository();
        $current = $repo->findByPaymentId($paymentId);
        if ($current === null) {
            throw new RuntimeException('Booking record not found.');
        }

        $updated = $callback($current);
        app_pdo()->prepare(
            'UPDATE appointments SET
                status = :status, meet_link = :meet_link, booked_at = :booked_at,
                emails_sent = :emails_sent, questionnaire_completed = :questionnaire_completed,
                error = :error, completed_at = :completed_at
             WHERE payment_id = :payment_id'
        )->execute([
            'status' => $updated['status'] ?? $current['status'],
            'meet_link' => $updated['meet_link'] ?? '',
            'booked_at' => $updated['booked_at'] ?? '',
            'emails_sent' => !empty($updated['emails_sent']) ? 1 : 0,
            'questionnaire_completed' => !empty($updated['questionnaire_completed']) ? 1 : 0,
            'error' => $updated['error'] ?? null,
            'completed_at' => $updated['completed_at'] ?? $current['completed_at'] ?? null,
            'payment_id' => $paymentId,
        ]);

        return $repo->findByPaymentId($paymentId) ?? $updated;
    }

    /** @return array<string, mixed> */
    public function deleteBySlot(string $date, string $time, string $phone, string $name): array
    {
        $sql = 'SELECT * FROM appointments WHERE date = :date AND time = :time';
        $params = ['date' => $date, 'time' => $time];
        if ($phone !== '') {
            $sql .= ' AND phone = :phone';
            $params['phone'] = $phone;
        }
        if ($name !== '') {
            $sql .= ' AND name = :name';
            $params['name'] = $name;
        }
        $stmt = app_pdo()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        $row = $stmt->fetch();
        if ($row === false) {
            throw new RuntimeException('Booking not found.');
        }
        app_pdo()->prepare('DELETE FROM appointments WHERE id = :id')->execute(['id' => $row['id']]);
        return $row;
    }
}
