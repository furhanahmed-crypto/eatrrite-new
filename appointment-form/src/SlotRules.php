<?php

declare(strict_types=1);

final class SlotRules
{
    /** @var array<string, true> */
    private array $disabledKeys;

    public function __construct(
        private SlotService $slots,
        private SlotGrid $grid,
        array $disabledKeys,
        private int $daysAhead
    ) {
        $this->disabledKeys = $disabledKeys;
    }

    public function availableTimesForDate(string $date, array $occupied = [], bool $hidePast = true): array
    {
        $times = $this->grid->startTimes($date, $occupied, $this->slots->parseDate($date));
        if ($hidePast) {
            $now = $this->slots->today();
            $times = array_values(array_filter(
                $times,
                fn (string $time): bool => $this->slots->slotStart($date, $time) > $now
            ));
        }
        return array_values(array_filter(
            $times,
            fn (string $time): bool => !$this->isDisabled($date, $time)
        ));
    }

    public function isOfferedTime(string $date, string $time): bool
    {
        $normalized = $this->slots->normalizeTime($time);
        return $normalized !== null && in_array($normalized, $this->slots->offeredTimesForDate($date), true);
    }

    public function isDisabled(string $date, string $time): bool
    {
        $normalized = $this->slots->normalizeTime($time);
        return $normalized !== null && isset($this->disabledKeys[$this->slots->slotKey($date, $normalized)]);
    }

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

    public function assertBookable(string $date, string $time, array $occupied): void
    {
        $normalized = $this->slots->normalizeTime($time);
        if ($normalized === null || !$this->isOfferedTime($date, $normalized) || $this->isDisabled($date, $normalized)) {
            throw new InvalidArgumentException('That time slot is not offered.');
        }
        $day = $this->slots->parseDate($date);
        $today = $this->slots->today()->setTime(0, 0, 0);
        if ($day < $today || $day > $this->slots->lastBookableDate()) {
            throw new InvalidArgumentException('Please choose a date within the next ' . $this->daysAhead . ' days.');
        }
        if ($this->slots->slotStart($date, $normalized) <= $this->slots->today()) {
            throw new InvalidArgumentException('That time has already passed. Pick another slot.');
        }
        if (!in_array($normalized, $this->availableTimesForDate($date, $occupied), true)) {
            throw new InvalidArgumentException('That slot was just booked. Please pick another time.');
        }
    }
}
