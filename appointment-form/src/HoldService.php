<?php

declare(strict_types=1);

final class HoldService
{
    private HoldRepository $holds;
    private int $holdMinutes;
    private SlotService $slots;

    public function __construct(array $config, SlotService $slots)
    {
        $this->holds = new HoldRepository();
        $this->holdMinutes = (int) $config['hold_minutes'];
        $this->slots = $slots;
    }

    /** @return list<array{date:string,time:string,order_id:string,expires_at:int}> */
    public function activeHolds(): array
    {
        return $this->holds->active();
    }

    public function hold(string $date, string $time, string $orderId): void
    {
        foreach ($this->holds->active() as $row) {
            if ($row['date'] === $date && $this->slots->timesOverlap($row['time'], $time)) {
                throw new InvalidArgumentException(
                    'That slot is on hold for another booking. Try a different time.'
                );
            }
        }

        $this->holds->add($date, $time, $orderId, time() + ($this->holdMinutes * 60));
    }

    public function releaseByOrder(string $orderId): void
    {
        $this->holds->releaseByOrder($orderId);
    }
}
