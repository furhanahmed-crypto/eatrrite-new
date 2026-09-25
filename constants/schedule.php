<?php

declare(strict_types=1);

/**
 * Weekly clinic hours. Empty day = closed.
 */
function appointment_schedule_settings(): array
{
    return [
        'customer_meeting_minutes' => 30,
        'consultant_prep_minutes' => 15,
        'fallback_interval_minutes' => 15,
        'weekly_hours' => [
            'monday' => [
                ['start' => '11:30', 'end' => '14:00'],
                ['start' => '15:30', 'end' => '17:30'],
                ['start' => '20:30', 'end' => '21:30'],
            ],
            'tuesday' => [
                ['start' => '11:30', 'end' => '14:00'],
                ['start' => '15:30', 'end' => '17:30'],
                ['start' => '20:30', 'end' => '21:30'],
            ],
            'wednesday' => [
                ['start' => '11:30', 'end' => '14:00'],
                ['start' => '15:30', 'end' => '17:30'],
                ['start' => '20:30', 'end' => '21:30'],
            ],
            'thursday' => [
                ['start' => '11:30', 'end' => '14:00'],
                ['start' => '15:30', 'end' => '17:30'],
                ['start' => '20:30', 'end' => '21:30'],
            ],
            'friday' => [],
            'saturday' => [
                ['start' => '11:30', 'end' => '14:30'],
            ],
            'sunday' => [
                ['start' => '11:30', 'end' => '13:00'],
            ],
        ],
    ];
}
