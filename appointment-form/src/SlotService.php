<?php

declare(strict_types=1);

final class SlotService
{
    private DateTimeZone $tz;
    private int $daysAhead;
    private int $meetingMinutes;
    private int $prepMinutes;
    private int $blockMinutes;
    private SlotHours $hours;
    private SlotGrid $grid;
    private SlotRules $rules;

    public function __construct(array $config)
    {
        $rules = $config['slot_times'] ?? [];
        if (!is_array($rules) || $rules === []) {
            throw new RuntimeException('Missing slot_schedule in constants/schedule.php.');
        }

        $this->tz = new DateTimeZone($config['timezone']);
        $this->daysAhead = (int) $config['booking_days_ahead'];
        $this->meetingMinutes = (int) $rules['customer_meeting_minutes'];
        $this->prepMinutes = (int) $rules['consultant_prep_minutes'];
        $this->blockMinutes = $this->meetingMinutes + $this->prepMinutes;
        $this->hours = new SlotHours($rules['weekly_hours'] ?? [], $this->tz);
        $this->grid = new SlotGrid(
            $this->tz,
            $this->hours,
            $this->meetingMinutes,
            $this->prepMinutes,
            $this->blockMinutes,
            (int) $rules['fallback_interval_minutes']
        );
        $this->rules = new SlotRules(
            $this,
            $this->grid,
            $this->grid->indexDisabled($config['disabled_slots'] ?? []),
            $this->daysAhead
        );
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

    public function weeklyHours(): array
    {
        return $this->hours->all();
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

    public function offeredTimesForDate(string $date): array
    {
        return $this->grid->startTimes($date, [], $this->parseDate($date));
    }

    public function availableTimesForDate(string $date, array $occupied = [], bool $hidePast = true): array
    {
        return $this->rules->availableTimesForDate($date, $occupied, $hidePast);
    }

    public function isOfferedTime(string $date, string $time): bool
    {
        return $this->rules->isOfferedTime($date, $time);
    }

    public function isDisabled(string $date, string $time): bool
    {
        return $this->rules->isDisabled($date, $time);
    }

    public function disabledTimesForDate(string $date): array
    {
        return $this->rules->disabledTimesForDate($date);
    }

    public function assertBookable(string $date, string $time, array $occupied): void
    {
        $this->rules->assertBookable($date, $time, $occupied);
    }

    public function slotStart(string $date, string $time): DateTimeImmutable
    {
        $normalized = $this->normalizeTime($time);
        $start = $normalized
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $normalized, $this->tz)
            : false;
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
        return $date . '|' . ($this->normalizeTime($time) ?? $time);
    }

    public function displayTime(string $time): string
    {
        $normalized = $this->normalizeTime($time);
        $parsed = $normalized ? DateTimeImmutable::createFromFormat('H:i', $normalized, $this->tz) : false;
        return $parsed ? $parsed->format('g:i A') : $time;
    }

    public function displayDate(string $date): string
    {
        return $this->parseDate($date)->format('D, j M Y');
    }

    public function publicHoursNote(): string
    {
        return $this->hours->publicNote(fn (string $time): string => $this->displayTime($time));
    }

    public function occupancyRows(array $booked, array $held): array
    {
        return $this->grid->occupancyRows($booked, $held, fn (string $date) => $this->parseDate($date));
    }

    public function timesOverlap(string $timeA, string $timeB): bool
    {
        $startA = SlotClock::toMinutes($timeA, $this->tz);
        $startB = SlotClock::toMinutes($timeB, $this->tz);
        if ($startA === null || $startB === null) {
            return $timeA === $timeB;
        }
        return $startA < ($startB + $this->blockMinutes) && $startB < ($startA + $this->blockMinutes);
    }

    public function normalizeTime(string $time): ?string
    {
        return SlotClock::parse($time, $this->tz);
    }

    public static function parseClock(string $time, DateTimeZone $tz): ?string
    {
        return SlotClock::parse($time, $tz);
    }
}
