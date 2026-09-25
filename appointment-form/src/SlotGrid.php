<?php

declare(strict_types=1);

final class SlotGrid
{
    public function __construct(
        private DateTimeZone $tz,
        private SlotHours $hours,
        private int $meetingMinutes,
        private int $prepMinutes,
        private int $blockMinutes,
        private int $fallbackMinutes
    ) {
    }

    /** @param list<array{date?:string,time?:string}> $occupied */
    public function startTimes(string $date, array $occupied, DateTimeImmutable $day): array
    {
        $windows = $this->hours->windowsFor($day, $this->meetingMinutes, $this->prepMinutes);
        if ($windows === []) {
            return [];
        }

        $blocks = $this->mergedOccupancy($date, $occupied);
        $starts = [];
        foreach ($windows as $window) {
            foreach ($this->startsForWindow($window, $blocks) as $minutes) {
                $starts[SlotClock::formatMinutes($minutes)] = true;
            }
        }
        $times = array_keys($starts);
        sort($times);
        return $times;
    }

    /** @return array<string, true> */
    public function indexDisabled(array $rows): array
    {
        $keys = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $date = trim((string) ($row['date'] ?? ''));
            $time = SlotClock::parse((string) ($row['time'] ?? ''), $this->tz);
            if ($date === '' || $time === null) {
                continue;
            }
            $keys[$date . '|' . $time] = true;
        }
        return $keys;
    }

    /** @return list<array{date:string,time:string}> */
    public function occupancyRows(array $booked, array $held, callable $parseDate): array
    {
        $unique = [];
        foreach (array_merge($booked, $held) as $row) {
            $date = trim((string) ($row['date'] ?? ''));
            $time = SlotClock::parse((string) ($row['time'] ?? ''), $this->tz);
            if ($date === '' || $time === null) {
                continue;
            }
            try {
                $parseDate($date);
            } catch (InvalidArgumentException) {
                continue;
            }
            $unique[$date . '|' . $time] = ['date' => $date, 'time' => $time];
        }
        return array_values($unique);
    }

    /** @param list<array{date?:string,time?:string}> $occupied */
    private function mergedOccupancy(string $date, array $occupied): array
    {
        $blocks = [];
        foreach ($occupied as $row) {
            if (trim((string) ($row['date'] ?? '')) !== $date) {
                continue;
            }
            $start = SlotClock::toMinutes((string) ($row['time'] ?? ''), $this->tz);
            if ($start === null) {
                continue;
            }
            $blocks[] = [$start, $start + $this->blockMinutes];
        }
        if ($blocks === []) {
            return [];
        }
        usort($blocks, static fn (array $a, array $b): int => $a[0] <=> $b[0]);
        $merged = [];
        foreach ($blocks as $block) {
            if ($merged === [] || $block[0] > $merged[array_key_last($merged)][1]) {
                $merged[] = $block;
                continue;
            }
            $last = array_key_last($merged);
            $merged[$last][1] = max($merged[$last][1], $block[1]);
        }
        return $merged;
    }

    private function startsForWindow(array $window, array $blocks): array
    {
        if ($window['latest_start'] < $window['start']) {
            return [];
        }
        $starts = [];
        for ($cursor = $window['start']; $cursor <= $window['latest_start']; $cursor += $this->fallbackMinutes) {
            $blockEnd = $cursor + $this->blockMinutes;
            if ($blockEnd > $window['capacity_end'] || $this->overlaps($cursor, $blockEnd, $blocks)) {
                continue;
            }
            $starts[] = $cursor;
        }
        return $starts;
    }

    private function overlaps(int $start, int $end, array $blocks): bool
    {
        foreach ($blocks as [$blockStart, $blockEnd]) {
            if ($start < $blockEnd && $blockStart < $end) {
                return true;
            }
        }
        return false;
    }
}
