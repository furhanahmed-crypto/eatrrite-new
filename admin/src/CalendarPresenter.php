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
     * @return list<array{iso:string,day:int,in_month:bool,is_today:bool,is_selected:bool,count:int}>
     */
    public static function monthCells(
        DateTimeImmutable $month,
        DateTimeImmutable $selected,
        DateTimeImmutable $today,
        array $byDate
    ): array {
        $start = $month->modify('first day of this month')->setTime(0, 0, 0);
        $cursor = $start->modify('-' . (int) $start->format('w') . ' days');
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
            ];
            $cursor = $cursor->modify('+1 day');
        }
        return $cells;
    }

    /** @param list<array<string, mixed>> $events @return list<array<string, mixed>> */
    public static function dayRows(array $events, SlotService $slots, DateTimeImmutable $day): array
    {
        $date = $day->format('Y-m-d');
        $offered = $slots->offeredTimesForDate($date);
        $byStart = [];
        foreach ($events as $event) {
            $byStart[(string) $event['time']][] = $event;
        }

        $times = $offered;
        foreach (array_keys($byStart) as $time) {
            if (!in_array($time, $times, true)) {
                $times[] = $time;
            }
        }
        usort($times, static fn (string $a, string $b): int => self::toMinutes($a) <=> self::toMinutes($b));

        $covered = [];
        $rows = [];
        foreach ($times as $time) {
            $starts = $byStart[$time] ?? [];
            if ($starts !== []) {
                $end = $slots->consultantSlotEnd($date, $time)->format('H:i');
                $startM = self::toMinutes($time);
                $endM = self::toMinutes($end);
                foreach ($times as $other) {
                    $otherM = self::toMinutes($other);
                    if ($otherM > $startM && $otherM < $endM) {
                        $covered[$other] = true;
                    }
                }
                $event = $starts[0];
                $rows[] = [
                    'kind' => 'booking',
                    'time' => $time,
                    'end_time' => $end,
                    'display_time' => $slots->displayTime($time) . ' – ' . $slots->displayTime($end),
                    'display_meeting' => $slots->displayTime($time) . ' – ' . $slots->displayTime(
                        $slots->slotEnd($date, $time)->format('H:i')
                    ),
                    'event' => $event,
                ];
                continue;
            }
            if (!empty($covered[$time])) {
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
        return $rows;
    }

    private static function toMinutes(string $time): int
    {
        [$hour, $minute] = array_map('intval', explode(':', $time));
        return ($hour * 60) + $minute;
    }
}
