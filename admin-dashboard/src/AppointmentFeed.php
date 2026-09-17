<?php

declare(strict_types=1);

/**
 * Admin calendar feed from Apps Script (no disk cache).
 */
final class AppointmentFeed
{
    private GoogleAppsScriptClient $sheet;
    private SlotService $slots;

    public function __construct(SlotService $slots, GoogleAppsScriptClient $sheet)
    {
        $this->slots = $slots;
        $this->sheet = $sheet;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        $rows = [];
        foreach ($this->sheet->listAppointments() as $row) {
            $enriched = $this->enrich($row);
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
     * @param array{date:string,time:string,name:string,service:string,phone:string,meet_link:string,booked_at:string} $row
     * @return array<string, mixed>|null
     */
    private function enrich(array $row): ?array
    {
        $date = $row['date'];
        $time = $row['time'];

        try {
            $start = $this->slots->slotStart($date, $time);
            $meetingEnd = $this->slots->slotEnd($date, $time);
            $blockEnd = $this->slots->consultantSlotEnd($date, $time);
        } catch (InvalidArgumentException) {
            return null;
        }

        return [
            'id' => $date . '|' . $time . '|' . md5($row['name'] . $row['phone'] . $row['service']),
            'name' => $row['name'] !== '' ? $row['name'] : 'Client',
            'service' => $row['service'],
            'phone' => $row['phone'],
            'date' => $date,
            'time' => $time,
            'display_date' => $this->slots->displayDate($date),
            'display_time' => $this->slots->displayTime($time),
            'meeting_end' => $meetingEnd->format('H:i'),
            'display_meeting_end' => $this->slots->displayTime($meetingEnd->format('H:i')),
            'block_end' => $blockEnd->format('H:i'),
            'display_block_end' => $this->slots->displayTime($blockEnd->format('H:i')),
            'meet_link' => $row['meet_link'],
            'booked_at' => $row['booked_at'],
            'start_minutes' => ((int) $start->format('G') * 60) + (int) $start->format('i'),
            'end_minutes' => ((int) $blockEnd->format('G') * 60) + (int) $blockEnd->format('i'),
            'color' => self::colorFor($row['service'] !== '' ? $row['service'] : $row['name']),
        ];
    }

    public static function colorFor(string $seed): string
    {
        $palette = [
            '#38640e',
            '#5a8c2a',
            '#2f4a28',
            '#6b8f71',
            '#c4a35a',
            '#f57e57',
            '#3d5340',
            '#7a9a4a',
            '#4b6b3c',
            '#9c7a3c',
        ];

        $index = (int) sprintf('%u', crc32($seed)) % count($palette);

        return $palette[$index];
    }
}
