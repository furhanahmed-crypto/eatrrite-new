<?php

declare(strict_types=1);

final class CalendarPresenter
{
    /**
     * @param list<array<string, mixed>> $appointments
     * @return array<string, list<array<string, mixed>>>
     */
    public static function groupByDate(array $appointments): array
    {
        $grouped = [];
        foreach ($appointments as $row) {
            $date = (string) ($row['date'] ?? '');
            if ($date === '') {
                continue;
            }
            $grouped[$date][] = $row;
        }

        return $grouped;
    }

    /**
     * @param array<string, list<array<string, mixed>>> $byDate
     * @return list<array{iso:string,day:int,in_month:bool,is_today:bool,is_selected:bool,count:int,events:list<array<string, mixed>>}>
     */
    public static function monthCells(DateTimeImmutable $month, DateTimeImmutable $selected, DateTimeImmutable $today, array $byDate): array
    {
        $start = $month->modify('first day of this month')->setTime(0, 0, 0);
        $pad = (int) $start->format('w');
        $cursor = $start->modify('-' . $pad . ' days');
        $cells = [];

        for ($i = 0; $i < 42; $i++) {
            $iso = $cursor->format('Y-m-d');
            $events = $byDate[$iso] ?? [];
            $cells[] = [
                'iso' => $iso,
                'day' => (int) $cursor->format('j'),
                'in_month' => $cursor->format('Y-m') === $month->format('Y-m'),
                'is_today' => $iso === $today->format('Y-m-d'),
                'is_selected' => $iso === $selected->format('Y-m-d'),
                'count' => count($events),
                'events' => $events,
            ];
            $cursor = $cursor->modify('+1 day');
        }

        return $cells;
    }

    /**
     * One row per 15-minute offered slot, plus any booked times not on the grid.
     *
     * @param list<array<string, mixed>> $events
     * @return array{
     *   windows:list<array{start:int,end:int,capacity_end:int,label:string}>,
     *   unavailable:bool,
     *   rows:list<array<string, mixed>>
     * }
     */
    public static function dayLayout(array $events, SlotService $slots, DateTimeImmutable $day): array
    {
        $date = $day->format('Y-m-d');
        $offered = $slots->offeredTimesForDate($date);
        $byStart = [];
        foreach ($events as $event) {
            $time = (string) $event['time'];
            $byStart[$time][] = $event;
        }

        $times = $offered;
        foreach (array_keys($byStart) as $time) {
            if (!in_array($time, $times, true)) {
                $times[] = $time;
            }
        }
        usort($times, static fn (string $a, string $b): int => self::toMinutes($a) <=> self::toMinutes($b));

        $rows = [];
        $previousMinutes = null;
        foreach ($times as $time) {
            $minutes = self::toMinutes($time);
            if ($previousMinutes !== null && ($minutes - $previousMinutes) > 15) {
                $rows[] = ['kind' => 'gap'];
            }
            $previousMinutes = $minutes;

            $startsHere = $byStart[$time] ?? [];
            if ($startsHere !== []) {
                foreach ($startsHere as $event) {
                    $rows[] = [
                        'kind' => 'booking',
                        'time' => $time,
                        'display_time' => $slots->displayTime($time),
                        'event' => $event,
                    ];
                }
                continue;
            }

            $blocker = self::blockingEvent($minutes, $events);
            if ($blocker !== null) {
                $rows[] = [
                    'kind' => 'busy',
                    'time' => $time,
                    'display_time' => $slots->displayTime($time),
                    'event' => $blocker,
                ];
                continue;
            }

            $hidden = $slots->isDisabled($date, $time);
            $rows[] = [
                'kind' => $hidden ? 'disabled' : 'open',
                'time' => $time,
                'display_time' => $slots->displayTime($time),
                'hidden' => $hidden,
            ];
        }

        $windows = self::windowsFor($slots, $day);

        return [
            'windows' => $windows,
            'unavailable' => $windows === [] && $events === [],
            'rows' => $rows,
        ];
    }

    /**
     * @return list<array{start:int,end:int,capacity_end:int,label:string}>
     */
    public static function windowsFor(SlotService $slots, DateTimeImmutable $day): array
    {
        $name = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday',
        ][(int) $day->format('N')] ?? '';

        $out = [];
        foreach ($slots->weeklyHours()[$name] ?? [] as $window) {
            $start = self::toMinutes($window['start']);
            $end = self::toMinutes($window['end']);
            $out[] = [
                'start' => $start,
                'end' => $end,
                'capacity_end' => $end + $slots->consultantPrepMinutes(),
                'label' => $slots->displayTime($window['start']) . '–' . $slots->displayTime($window['end']),
            ];
        }

        return $out;
    }

    /**
     * @param list<array<string, mixed>> $events
     * @return array<string, mixed>|null
     */
    private static function blockingEvent(int $minutes, array $events): ?array
    {
        foreach ($events as $event) {
            $start = (int) $event['start_minutes'];
            $end = (int) $event['end_minutes'];
            if ($minutes >= $start && $minutes < $end) {
                return $event;
            }
        }

        return null;
    }

    private static function toMinutes(string $time): int
    {
        [$hour, $minute] = array_map('intval', explode(':', $time));

        return ($hour * 60) + $minute;
    }
}
