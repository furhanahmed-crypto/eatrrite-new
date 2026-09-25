<?php

declare(strict_types=1);

final class QuestionnaireReminders
{
    /** @var list<int> */
    private array $hours;
    private int $stopAfterHours;

    public function __construct()
    {
        $spec = require dirname(__DIR__, 2) . '/constants/questionnaire-reminders.php';
        $this->hours = array_values(array_map('intval', $spec['hours']));
        $this->stopAfterHours = (int) $spec['stop_after_hours'];
    }

    /** @return array{checked:int, sent:int, dry:bool} */
    public function run(bool $dryRun = false): array
    {
        $sent = 0;
        $rows = $this->pending();
        foreach ($rows as $row) {
            $hour = $this->nextDue($row);
            if ($hour === null) {
                continue;
            }
            if ($this->send($row, $hour, $dryRun)) {
                $sent++;
            }
        }

        return ['checked' => count($rows), 'sent' => $sent, 'dry' => $dryRun];
    }

    /** @return list<array<string, mixed>> */
    private function pending(): array
    {
        $min = time() - ($this->stopAfterHours * 3600);
        $stmt = app_pdo()->prepare(
            "SELECT * FROM appointments
             WHERE questionnaire_completed = 0
               AND status IN ('confirmed', 'verified')
               AND email <> ''
               AND verified_at IS NOT NULL
               AND verified_at >= :min
             ORDER BY verified_at"
        );
        $stmt->execute(['min' => $min]);
        return $stmt->fetchAll();
    }

    /** @param array<string, mixed> $row */
    private function nextDue(array $row): ?int
    {
        $paidAt = (int) ($row['verified_at'] ?? 0);
        if ($paidAt <= 0) {
            return null;
        }
        $elapsed = (time() - $paidAt) / 3600;
        $sent = $this->sentHours($row);
        foreach ($this->hours as $hour) {
            if ($elapsed >= $hour && !in_array($hour, $sent, true)) {
                return $hour;
            }
        }
        return null;
    }

    /** @param array<string, mixed> $row @return list<int> */
    private function sentHours(array $row): array
    {
        $raw = trim((string) ($row['questionnaire_reminders_sent'] ?? ''));
        if ($raw === '') {
            return [];
        }
        return array_values(array_filter(array_map('intval', explode(',', $raw))));
    }

    /** @param array<string, mixed> $row */
    private function send(array $row, int $hour, bool $dryRun): bool
    {
        $fresh = (new BookingStore())->findById((string) $row['id']);
        if ($fresh === null || !empty($fresh['questionnaire_completed'])) {
            return false;
        }

        if ($dryRun) {
            return true;
        }

        try {
            require_once dirname(__DIR__, 2) . '/email/AppointmentEmails.php';
            send_customer_questionnaire_reminder_email([
                'name' => (string) $row['name'],
                'email' => (string) $row['email'],
                'service' => (string) $row['service'],
                'display_date' => (string) $row['display_date'],
                'display_time' => (string) $row['display_time'],
                'reminder_hour' => $hour,
                'questionnaire_url' => appointment_public_url(
                    'appointment-confirmed.php?appointmentId=' . rawurlencode((string) $row['id'])
                ),
            ]);
            $this->markSent((string) $row['id'], $this->sentHours($row), $hour);
            return true;
        } catch (Throwable $e) {
            error_log('[reminders] ' . $row['id'] . ' @' . $hour . 'h: ' . $e->getMessage());
            return false;
        }
    }

    /** @param list<int> $already */
    private function markSent(string $appointmentId, array $already, int $hour): void
    {
        $already[] = $hour;
        $already = array_values(array_unique($already));
        sort($already);
        $stmt = app_pdo()->prepare(
            'UPDATE appointments
             SET questionnaire_reminders_sent = :sent
             WHERE id = :id AND questionnaire_completed = 0'
        );
        $stmt->execute([
            'sent' => implode(',', $already),
            'id' => $appointmentId,
        ]);
    }
}
