<?php

declare(strict_types=1);

final class AppointmentFeed
{
    public function __construct(
        private SlotService $slots,
        private BookingStore $bookings
    ) {
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        $raw = $this->bookings->allCompleted();
        $answers = (new QuestionnaireRepository())->forAppointments(array_map(
            static fn (array $row): string => (string) ($row['id'] ?? ''),
            $raw
        ));
        $rows = [];
        foreach ($raw as $row) {
            $enriched = $this->enrich($row, $answers[(string) ($row['id'] ?? '')] ?? []);
            if ($enriched !== null) {
                $rows[] = $enriched;
            }
        }

        usort($rows, static function (array $a, array $b): int {
            return [$a['date'], $a['time']] <=> [$b['date'], $b['time']];
        });

        return $rows;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, string> $answers
     */
    private function enrich(array $row, array $answers): ?array
    {
        $date = (string) ($row['date'] ?? '');
        $time = (string) ($row['time'] ?? '');
        try {
            $start = $this->slots->slotStart($date, $time);
            $meetingEnd = $this->slots->slotEnd($date, $time);
            $blockEnd = $this->slots->consultantSlotEnd($date, $time);
        } catch (InvalidArgumentException) {
            return null;
        }

        $name = (string) ($row['name'] ?? '');
        $service = appointment_service_label((string) ($row['service'] ?? ''));

        return [
            'id' => $date . '|' . $time . '|' . md5($name . ($row['phone'] ?? '') . $service),
            'name' => $name !== '' ? $name : 'Client',
            'service' => $service,
            'phone' => (string) ($row['phone'] ?? ''),
            'date' => $date,
            'time' => $time,
            'display_date' => $this->slots->displayDate($date),
            'display_time' => $this->slots->displayTime($time),
            'meeting_end' => $meetingEnd->format('H:i'),
            'display_meeting_end' => $this->slots->displayTime($meetingEnd->format('H:i')),
            'block_end' => $blockEnd->format('H:i'),
            'display_block_end' => $this->slots->displayTime($blockEnd->format('H:i')),
            'email' => (string) ($row['email'] ?? ''),
            'meet_link' => (string) ($row['meet_link'] ?? ''),
            'booked_at' => (string) ($row['booked_at'] ?? ''),
            'questionnaire' => $this->labeledAnswers($answers),
            'start_minutes' => ((int) $start->format('G') * 60) + (int) $start->format('i'),
            'end_minutes' => ((int) $blockEnd->format('G') * 60) + (int) $blockEnd->format('i'),
        ];
    }

    /**
     * @param array<string, string> $answers
     * @return list<array{label:string,answer:string}>
     */
    private function labeledAnswers(array $answers): array
    {
        if ($answers === []) {
            return [];
        }
        $spec = require dirname(__DIR__, 2) . '/constants/questionnaire.php';
        $labeled = [];
        foreach ($spec['steps'] as $step) {
            $key = (string) $step['key'];
            if (!array_key_exists($key, $answers)) {
                continue;
            }
            $labeled[] = [
                'label' => (string) $step['label'],
                'answer' => $this->displayAnswer($answers[$key]),
            ];
            $follow = (string) ($step['follow_key'] ?? '');
            if ($follow !== '' && trim((string) ($answers[$follow] ?? '')) !== '') {
                $labeled[] = [
                    'label' => (string) ($step['follow_label'] ?? $follow),
                    'answer' => (string) $answers[$follow],
                ];
            }
        }
        return $labeled;
    }

    private function displayAnswer(string $raw): string
    {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return implode(', ', array_map('strval', $decoded));
        }
        return $raw !== '' ? $raw : '—';
    }
}
