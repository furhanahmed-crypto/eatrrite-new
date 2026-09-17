<?php

declare(strict_types=1);

/**
 * Hidden slots from Google Sheet via Apps Script (no local JSON cache).
 */
final class DisabledSlotsStore
{
    private GoogleAppsScriptClient $sheet;

    public function __construct(array $config, ?GoogleAppsScriptClient $sheet = null)
    {
        $this->sheet = $sheet ?? new GoogleAppsScriptClient($config);
    }

    /**
     * @return list<array{date:string,time:string}>
     */
    public function all(): array
    {
        try {
            return $this->sheet->listDisabledSlots();
        } catch (Throwable) {
            return [];
        }
    }

    public function set(string $date, string $time, bool $hidden): void
    {
        $this->sheet->setDisabledSlot($date, $time, $hidden);
    }
}
