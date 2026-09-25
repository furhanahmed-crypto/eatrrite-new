<?php

declare(strict_types=1);

final class DisabledSlotsStore
{
    private DisabledSlotRepository $slots;

    public function __construct(array $config = [], ?DisabledSlotRepository $slots = null)
    {
        unset($config);
        $this->slots = $slots ?? new DisabledSlotRepository();
    }

    /** @return list<array{date:string,time:string}> */
    public function all(): array
    {
        return $this->slots->all();
    }

    public function set(string $date, string $time, bool $hidden): void
    {
        $this->slots->set($date, $time, $hidden);
    }
}
