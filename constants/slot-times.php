<?php

declare(strict_types=1);

/**
 * @return array{
 *   customer_meeting_minutes:int,
 *   consultant_prep_minutes:int,
 *   fallback_interval_minutes:int,
 *   weekly_hours:array<string, list<array{start:string,end:string}>>,
 *   source:string
 * }
 */
function appointment_slot_times(?array $config = null): array
{
    $schedule = $config['slot_schedule'] ?? appointment_schedule_settings();
    if (!is_array($schedule) || $schedule === []) {
        throw new RuntimeException('Missing slot_schedule in constants/schedule.php.');
    }

    $requiredPositive = static function (array $settings, string $key): int {
        $value = (int) ($settings[$key] ?? 0);
        if ($value <= 0) {
            throw new RuntimeException('slot_schedule.' . $key . ' must be a positive number.');
        }
        return $value;
    };

    $weeklyIn = $schedule['weekly_hours'] ?? null;
    if (!is_array($weeklyIn)) {
        throw new RuntimeException('slot_schedule.weekly_hours must be an array.');
    }

    $weekly = [
        'monday' => [], 'tuesday' => [], 'wednesday' => [], 'thursday' => [],
        'friday' => [], 'saturday' => [], 'sunday' => [],
    ];
    foreach ($weekly as $day => $_) {
        $windows = is_array($weeklyIn[$day] ?? null) ? $weeklyIn[$day] : [];
        foreach ($windows as $window) {
            if (!is_array($window)) {
                continue;
            }
            $start = trim((string) ($window['start'] ?? ''));
            $end = trim((string) ($window['end'] ?? ''));
            if ($start !== '' && $end !== '') {
                $weekly[$day][] = ['start' => $start, 'end' => $end];
            }
        }
    }

    return [
        'customer_meeting_minutes' => $requiredPositive($schedule, 'customer_meeting_minutes'),
        'consultant_prep_minutes' => $requiredPositive($schedule, 'consultant_prep_minutes'),
        'fallback_interval_minutes' => $requiredPositive($schedule, 'fallback_interval_minutes'),
        'weekly_hours' => $weekly,
        'source' => 'config',
    ];
}
