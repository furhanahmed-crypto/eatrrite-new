<?php

declare(strict_types=1);

final class SlotService
{
    private const WEEKDAYS = [
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    ];

    private DateTimeZone $tz;
    private int $daysAhead;
    private int $meetingMinutes;
    private int $prepMinutes;
    private int $blockMinutes;
    private int $fallbackMinutes;

    /** @var array<string, list<array{start:string,end:string}>> */
    private array $weeklyHours;

    /** @var array<string, true> */
    private array $disabledKeys = [];

    public function __construct(array $config)
    {
        $rules = $config['slot_times'] ?? [];
        if (!is_array($rules) || $rules === []) {
            throw new RuntimeException('Missing slot-times-config from the Google Sheet tab.');
        }

        $this->tz = new DateTimeZone($config['timezone']);
        $this->daysAhead = (int) $config['booking_days_ahead'];
        $this->meetingMinutes = (int) $rules['customer_meeting_minutes'];
        $this->prepMinutes = (int) $rules['consultant_prep_minutes'];
        $this->fallbackMinutes = (int) $rules['fallback_interval_minutes'];
        $this->blockMinutes = $this->meetingMinutes + $this->prepMinutes;
        $this->weeklyHours = $this->normalizeWeeklyHours($rules['weekly_hours'] ?? []);
        $this->disabledKeys = $this->indexDisabled($config['disabled_slots'] ?? []);

        if ($this->meetingMinutes <= 0 || $this->prepMinutes < 0 || $this->fallbackMinutes <= 0) {
            throw new RuntimeException('Invalid slot duration constants in slot-times-config.');
        }
    }

    public function customerMeetingMinutes(): int
    {
        return $this->meetingMinutes;
    }

    public function consultantPrepMinutes(): int
    {
        return $this->prepMinutes;
    }

    public function consultantBlockMinutes(): int
    {
        return $this->blockMinutes;
    }

    /**
     * @return array<string, list<array{start:string,end:string}>>
     */
    public function weeklyHours(): array
    {
        return $this->weeklyHours;
    }

    public function today(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->tz);
    }

    public function lastBookableDate(): DateTimeImmutable
    {
        return $this->today()->setTime(0, 0, 0)->modify('+' . $this->daysAhead . ' days');
    }

    public function parseDate(string $date): DateTimeImmutable
    {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date, $this->tz);

        if ($parsed === false || $parsed->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException('Choose a valid appointment date.');
        }

        return $parsed;
    }

    public function isOfferedTime(string $date, string $time): bool
    {
        $normalized = $this->normalizeTime($time);

        return $normalized !== null && in_array($normalized, $this->offeredTimesForDate($date), true);
    }

    /**
     * Start times this weekday would offer with no bookings (includes past and hidden times).
     *
     * @return list<string>
     */
    public function offeredTimesForDate(string $date): array
    {
        return $this->startTimesForDate($date, []);
    }

    /**
     * Open start times after applying occupancy, hidden slots, and (by default) past-time filtering.
     *
     * @param list<array{date?:string,time?:string}> $occupied
     * @return list<string>
     */
    public function availableTimesForDate(string $date, array $occupied = [], bool $hidePast = true): array
    {
        $times = $this->startTimesForDate($date, $occupied);

        if ($hidePast) {
            $now = $this->today();
            $times = array_values(array_filter(
                $times,
                fn (string $time): bool => $this->slotStart($date, $time) > $now
            ));
        }

        return array_values(array_filter(
            $times,
            fn (string $time): bool => !$this->isDisabled($date, $time)
        ));
    }

    public function isDisabled(string $date, string $time): bool
    {
        $normalized = $this->normalizeTime($time);
        if ($normalized === null) {
            return false;
        }

        return isset($this->disabledKeys[$this->slotKey($date, $normalized)]);
    }

    /**
     * @return list<string>
     */
    public function disabledTimesForDate(string $date): array
    {
        $prefix = $date . '|';
        $times = [];
        foreach (array_keys($this->disabledKeys) as $key) {
            if (str_starts_with($key, $prefix)) {
                $times[] = substr($key, strlen($prefix));
            }
        }

        return $times;
    }

    /**
     * @param list<array{date?:string,time?:string}> $occupied
     */
    public function assertBookable(string $date, string $time, array $occupied): void
    {
        $normalized = $this->normalizeTime($time);
        if ($normalized === null || !$this->isOfferedTime($date, $normalized)) {
            throw new InvalidArgumentException('That time slot is not offered.');
        }

        if ($this->isDisabled($date, $normalized)) {
            throw new InvalidArgumentException('That time slot is not offered.');
        }

        $day = $this->parseDate($date);
        $today = $this->today()->setTime(0, 0, 0);

        if ($day < $today) {
            throw new InvalidArgumentException('Please choose a future date.');
        }

        if ($day > $this->lastBookableDate()) {
            throw new InvalidArgumentException('Please choose a date within the next ' . $this->daysAhead . ' days.');
        }

        $start = $this->slotStart($date, $normalized);
        if ($start <= $this->today()) {
            throw new InvalidArgumentException('That time has already passed. Pick another slot.');
        }

        $open = $this->availableTimesForDate($date, $occupied);
        if (!in_array($normalized, $open, true)) {
            throw new InvalidArgumentException('That slot was just booked. Please pick another time.');
        }
    }

    public function slotStart(string $date, string $time): DateTimeImmutable
    {
        $normalized = $this->normalizeTime($time);
        if ($normalized === null) {
            throw new InvalidArgumentException('Invalid date or time.');
        }

        $start = DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $normalized, $this->tz);
        if ($start === false) {
            throw new InvalidArgumentException('Invalid date or time.');
        }

        return $start;
    }

    public function slotEnd(string $date, string $time): DateTimeImmutable
    {
        return $this->slotStart($date, $time)->modify('+' . $this->meetingMinutes . ' minutes');
    }

    public function consultantSlotEnd(string $date, string $time): DateTimeImmutable
    {
        return $this->slotStart($date, $time)->modify('+' . $this->blockMinutes . ' minutes');
    }

    public function slotKey(string $date, string $time): string
    {
        $normalized = $this->normalizeTime($time) ?? $time;

        return $date . '|' . $normalized;
    }

    public function displayTime(string $time): string
    {
        $normalized = $this->normalizeTime($time);
        if ($normalized === null) {
            return $time;
        }

        $parsed = DateTimeImmutable::createFromFormat('H:i', $normalized, $this->tz);

        return $parsed ? $parsed->format('g:i A') : $time;
    }

    public function displayDate(string $date): string
    {
        return $this->parseDate($date)->format('D, j M Y');
    }

    public function publicHoursNote(): string
    {
        $parts = [];
        $pendingDays = [];
        $pendingHours = null;

        $flush = function () use (&$parts, &$pendingDays, &$pendingHours): void {
            if ($pendingHours === null || $pendingDays === []) {
                return;
            }

            $parts[] = implode('–', $this->compactDayRange($pendingDays)) . ' ' . $pendingHours;
            $pendingDays = [];
            $pendingHours = null;
        };

        foreach (self::WEEKDAYS as $name) {
            $hours = $this->formatWindows($this->weeklyHours[$name] ?? []);
            if ($pendingHours !== null && $hours !== $pendingHours) {
                $flush();
            }
            $pendingHours = $hours;
            $pendingDays[] = $this->shortWeekday($name);
        }
        $flush();

        return implode(' · ', $parts);
    }

    /**
     * Normalize sheet/hold rows, drop blanks, and collapse duplicate date+time pairs.
     *
     * @param list<array{date?:string,time?:string}> $booked
     * @param list<array{date?:string,time?:string}> $held
     * @return list<array{date:string,time:string}>
     */
    public function occupancyRows(array $booked, array $held): array
    {
        $unique = [];

        foreach (array_merge($booked, $held) as $row) {
            $date = trim((string) ($row['date'] ?? ''));
            $time = $this->normalizeTime((string) ($row['time'] ?? ''));
            if ($date === '' || $time === null) {
                continue;
            }

            try {
                $this->parseDate($date);
            } catch (InvalidArgumentException) {
                continue;
            }

            $unique[$this->slotKey($date, $time)] = [
                'date' => $date,
                'time' => $time,
            ];
        }

        return array_values($unique);
    }

    public function timesOverlap(string $timeA, string $timeB): bool
    {
        $startA = $this->toMinutes($timeA);
        $startB = $this->toMinutes($timeB);
        if ($startA === null || $startB === null) {
            return $timeA === $timeB;
        }

        return $startA < ($startB + $this->blockMinutes) && $startB < ($startA + $this->blockMinutes);
    }

    /**
     * @param array<string, mixed> $weekly
     * @return array<string, list<array{start:string,end:string}>>
     */
    private function normalizeWeeklyHours(array $weekly): array
    {
        $hours = [];

        foreach (self::WEEKDAYS as $name) {
            $windows = $weekly[$name] ?? [];
            if (!is_array($windows)) {
                throw new RuntimeException('weekly_hours.' . $name . ' must be a list of start/end windows.');
            }

            $normalized = [];
            foreach ($windows as $window) {
                if (!is_array($window)) {
                    continue;
                }

                $start = $this->normalizeTime((string) ($window['start'] ?? $window[0] ?? ''));
                $end = $this->normalizeTime((string) ($window['end'] ?? $window[1] ?? ''));
                if ($start === null || $end === null) {
                    throw new RuntimeException('Invalid start/end in weekly_hours.' . $name . '. Use HH:MM.');
                }

                $startMinutes = $this->toMinutes($start);
                $endMinutes = $this->toMinutes($end);
                if ($startMinutes === null || $endMinutes === null || $endMinutes <= $startMinutes) {
                    throw new RuntimeException('Availability window in weekly_hours.' . $name . ' must end after it starts.');
                }

                $normalized[] = ['start' => $start, 'end' => $end];
            }

            $hours[$name] = $normalized;
        }

        return $hours;
    }

    /**
     * 15-minute start times after occupancy, before hidden-slot and past-time filters.
     *
     * @param list<array{date?:string,time?:string}> $occupied
     * @return list<string>
     */
    private function startTimesForDate(string $date, array $occupied = []): array
    {
        $day = $this->parseDate($date);
        $windows = $this->windowsForDate($day);
        if ($windows === []) {
            return [];
        }

        $blocks = $this->mergedOccupancyForDate($date, $occupied);
        $starts = [];

        foreach ($windows as $window) {
            foreach ($this->startsForWindow($window, $blocks) as $minutes) {
                $starts[$this->formatMinutes($minutes)] = true;
            }
        }

        $times = array_keys($starts);
        sort($times);

        return $times;
    }

    /**
     * @param list<array{date?:string,time?:string}> $rows
     * @return array<string, true>
     */
    private function indexDisabled(array $rows): array
    {
        $keys = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $date = trim((string) ($row['date'] ?? ''));
            $time = $this->normalizeTime((string) ($row['time'] ?? ''));
            if ($date === '' || $time === null) {
                continue;
            }
            $keys[$this->slotKey($date, $time)] = true;
        }

        return $keys;
    }

    /**
     * @return list<array{start:int,end:int,latest_start:int,capacity_end:int}>
     */
    private function windowsForDate(DateTimeImmutable $day): array
    {
        $name = self::WEEKDAYS[(int) $day->format('N')] ?? '';
        $windows = [];

        foreach ($this->weeklyHours[$name] ?? [] as $window) {
            $start = $this->toMinutes($window['start']);
            $end = $this->toMinutes($window['end']);
            if ($start === null || $end === null) {
                continue;
            }

            $windows[] = [
                'start' => $start,
                'end' => $end,
                'latest_start' => $end - $this->meetingMinutes,
                'capacity_end' => $end + $this->prepMinutes,
            ];
        }

        return $windows;
    }

    /**
     * @param list<array{date?:string,time?:string}> $occupied
     * @return list<array{0:int,1:int}>
     */
    private function mergedOccupancyForDate(string $date, array $occupied): array
    {
        $blocks = [];

        foreach ($occupied as $row) {
            if (trim((string) ($row['date'] ?? '')) !== $date) {
                continue;
            }

            $start = $this->toMinutes((string) ($row['time'] ?? ''));
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

    /**
     * Offer every 15-minute start in the window. Hide any start whose 45-minute
     * consultant block would overlap an existing booking.
     *
     * @param array{start:int,end:int,latest_start:int,capacity_end:int} $window
     * @param list<array{0:int,1:int}> $blocks
     * @return list<int>
     */
    private function startsForWindow(array $window, array $blocks): array
    {
        if ($window['latest_start'] < $window['start']) {
            return [];
        }

        $starts = [];
        for ($cursor = $window['start']; $cursor <= $window['latest_start']; $cursor += $this->fallbackMinutes) {
            $blockEnd = $cursor + $this->blockMinutes;
            if ($blockEnd > $window['capacity_end']) {
                continue;
            }
            if ($this->overlapsAny($cursor, $blockEnd, $blocks)) {
                continue;
            }
            $starts[] = $cursor;
        }

        return $starts;
    }

    /**
     * @param list<array{0:int,1:int}> $blocks
     */
    private function overlapsAny(int $start, int $end, array $blocks): bool
    {
        foreach ($blocks as [$blockStart, $blockEnd]) {
            if ($start < $blockEnd && $blockStart < $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<array{start:string,end:string}> $windows
     */
    private function formatWindows(array $windows): string
    {
        if ($windows === []) {
            return 'unavailable';
        }

        $parts = [];
        foreach ($windows as $window) {
            $parts[] = $this->displayTime($window['start']) . '–' . $this->displayTime($window['end']);
        }

        return implode(', ', $parts);
    }

    /**
     * @param list<string> $days
     * @return list<string>
     */
    private function compactDayRange(array $days): array
    {
        if (count($days) <= 2) {
            return $days;
        }

        return [$days[0] . '–' . $days[array_key_last($days)]];
    }

    private function shortWeekday(string $name): string
    {
        return [
            'monday' => 'Mon',
            'tuesday' => 'Tue',
            'wednesday' => 'Wed',
            'thursday' => 'Thu',
            'friday' => 'Fri',
            'saturday' => 'Sat',
            'sunday' => 'Sun',
        ][$name] ?? $name;
    }

    public function normalizeTime(string $time): ?string
    {
        return self::parseClock($time, $this->tz);
    }

    public static function parseClock(string $time, DateTimeZone $tz): ?string
    {
        $time = trim($time);
        if ($time === '') {
            return null;
        }

        if (is_numeric($time)) {
            $serial = (float) $time;
            if ($serial >= 0 && $serial < 2) {
                $minutes = (int) round(fmod($serial, 1.0) * 24 * 60);
                if ($minutes >= 24 * 60) {
                    $minutes = 0;
                }

                return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
            }
        }

        $formats = ['H:i', 'G:i', 'H:i:s', 'G:i:s', 'h:i A', 'g:i A', 'h:i a', 'g:i a', 'h:i:s A', 'g:i:s A'];
        foreach ($formats as $format) {
            $parsed = DateTimeImmutable::createFromFormat('!' . $format, $time, $tz);
            if ($parsed instanceof DateTimeImmutable) {
                return $parsed->format('H:i');
            }
        }

        if (preg_match('/\b(\d{1,2}):([0-5]\d)(?::[0-5]\d)?(?:\s*([AaPp][Mm]))?\b/', $time, $match) === 1) {
            $hour = (int) $match[1];
            $minute = (int) $match[2];
            $meridiem = strtoupper($match[3] ?? '');
            if ($meridiem === 'PM' && $hour < 12) {
                $hour += 12;
            }
            if ($meridiem === 'AM' && $hour === 12) {
                $hour = 0;
            }
            if ($hour <= 23) {
                return sprintf('%02d:%02d', $hour, $minute);
            }
        }

        try {
            $parsed = new DateTimeImmutable($time, $tz);

            return $parsed->format('H:i');
        } catch (Exception) {
            return null;
        }
    }

    private function toMinutes(string $time): ?int
    {
        $normalized = $this->normalizeTime($time);
        if ($normalized === null) {
            return null;
        }

        [$hour, $minute] = array_map('intval', explode(':', $normalized));

        return ($hour * 60) + $minute;
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $mins);
    }
}
