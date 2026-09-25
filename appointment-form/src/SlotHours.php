<?php

declare(strict_types=1);

final class SlotHours
{
    public const WEEKDAYS = [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ];

    /** @var array<string, list<array{start:string,end:string}>> */
    private array $weekly;

    public function __construct(array $weekly, private DateTimeZone $tz)
    {
        $this->weekly = $this->normalize($weekly);
    }

    /** @return array<string, list<array{start:string,end:string}>> */
    public function all(): array
    {
        return $this->weekly;
    }

    /** @return list<array{start:int,end:int,latest_start:int,capacity_end:int}> */
    public function windowsFor(DateTimeImmutable $day, int $meetingMinutes, int $prepMinutes): array
    {
        $name = self::WEEKDAYS[(int) $day->format('N')] ?? '';
        $windows = [];
        foreach ($this->weekly[$name] ?? [] as $window) {
            $start = SlotClock::toMinutes($window['start'], $this->tz);
            $end = SlotClock::toMinutes($window['end'], $this->tz);
            if ($start === null || $end === null) {
                continue;
            }
            $windows[] = [
                'start' => $start,
                'end' => $end,
                'latest_start' => $end - $meetingMinutes,
                'capacity_end' => $end + $prepMinutes,
            ];
        }
        return $windows;
    }

    public function publicNote(callable $displayTime): string
    {
        $parts = [];
        $pendingDays = [];
        $pendingHours = null;
        $flush = function () use (&$parts, &$pendingDays, &$pendingHours): void {
            if ($pendingHours === null || $pendingDays === []) {
                return;
            }
            $label = count($pendingDays) > 2
                ? $pendingDays[0] . '–' . $pendingDays[array_key_last($pendingDays)]
                : implode('–', $pendingDays);
            $parts[] = $label . ' ' . $pendingHours;
            $pendingDays = [];
            $pendingHours = null;
        };

        foreach (self::WEEKDAYS as $name) {
            $hours = $this->formatWindows($this->weekly[$name] ?? [], $displayTime);
            if ($pendingHours !== null && $hours !== $pendingHours) {
                $flush();
            }
            $pendingHours = $hours;
            $pendingDays[] = ucfirst(substr($name, 0, 3));
        }
        $flush();
        return implode(' · ', $parts);
    }

    /** @param array<string, mixed> $weekly */
    private function normalize(array $weekly): array
    {
        $hours = [];
        foreach (self::WEEKDAYS as $name) {
            $windows = $weekly[$name] ?? [];
            if (!is_array($windows)) {
                throw new RuntimeException('weekly_hours.' . $name . ' must be a list.');
            }
            $normalized = [];
            foreach ($windows as $window) {
                if (!is_array($window)) {
                    continue;
                }
                $start = SlotClock::parse((string) ($window['start'] ?? ''), $this->tz);
                $end = SlotClock::parse((string) ($window['end'] ?? ''), $this->tz);
                if ($start === null || $end === null) {
                    throw new RuntimeException('Invalid start/end in weekly_hours.' . $name . '.');
                }
                $startM = SlotClock::toMinutes($start, $this->tz);
                $endM = SlotClock::toMinutes($end, $this->tz);
                if ($startM === null || $endM === null || $endM <= $startM) {
                    throw new RuntimeException('Availability window in weekly_hours.' . $name . ' is invalid.');
                }
                $normalized[] = ['start' => $start, 'end' => $end];
            }
            $hours[$name] = $normalized;
        }
        return $hours;
    }

    private function formatWindows(array $windows, callable $displayTime): string
    {
        if ($windows === []) {
            return 'unavailable';
        }
        $parts = [];
        foreach ($windows as $window) {
            $parts[] = $displayTime($window['start']) . '–' . $displayTime($window['end']);
        }
        return implode(', ', $parts);
    }
}
