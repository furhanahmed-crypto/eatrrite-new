<?php

declare(strict_types=1);

final class SlotTimesStore
{
    private GoogleAppsScriptClient $sheet;
    private string $cacheFile;
    private int $ttlSeconds = 300;

    public function __construct(array $config, ?GoogleAppsScriptClient $sheet = null)
    {
        $this->sheet = $sheet ?? new GoogleAppsScriptClient($config);
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create appointment data directory.');
        }
        $this->cacheFile = $dir . DIRECTORY_SEPARATOR . 'slot-times-cache.json';
    }

    /**
     * @return array{
     *   customer_meeting_minutes:int,
     *   consultant_prep_minutes:int,
     *   fallback_interval_minutes:int,
     *   weekly_hours:array<string, list<array{start:string,end:string}>>,
     *   source:string
     * }
     */
    public function load(): array
    {
        $cached = $this->readCache();
        if (is_array($cached)) {
            return $cached;
        }

        $fromSheet = $this->loadFromSheet();
        $this->writeCache($fromSheet);

        return $fromSheet;
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFromSheet(): array
    {
        if (!$this->sheet->isConfigured()) {
            throw new RuntimeException('Google Sheet is not configured, so slot hours cannot be loaded.');
        }

        $payload = $this->sheet->listSlotTimesConfig();
        if (empty($payload['found'])) {
            throw new RuntimeException('The slot-times-config tab is missing or empty. Import the CSV into that tab.');
        }

        $parsed = $this->parseSheetPayload($payload);
        $parsed['source'] = 'sheet';

        return $parsed;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array{
     *   customer_meeting_minutes:int,
     *   consultant_prep_minutes:int,
     *   fallback_interval_minutes:int,
     *   weekly_hours:array<string, list<array{start:string,end:string}>>
     * }
     */
    public function parseSheetPayload(array $payload): array
    {
        $tz = new DateTimeZone('Asia/Kolkata');
        $settings = is_array($payload['settings'] ?? null) ? $payload['settings'] : [];
        $windows = is_array($payload['windows'] ?? null) ? $payload['windows'] : [];

        $meeting = $this->requiredInt($settings, 'customer_meeting_minutes');
        $prep = $this->requiredInt($settings, 'consultant_prep_minutes');
        $fallback = $this->requiredInt($settings, 'fallback_interval_minutes');

        $weekly = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];

        $sawWindowRow = false;
        foreach ($windows as $window) {
            if (!is_array($window)) {
                continue;
            }
            $day = strtolower(trim((string) ($window['weekday'] ?? '')));
            if (!array_key_exists($day, $weekly)) {
                continue;
            }
            $sawWindowRow = true;
            $start = SlotService::parseClock((string) ($window['start'] ?? ''), $tz);
            $end = SlotService::parseClock((string) ($window['end'] ?? ''), $tz);
            if ($start === null || $end === null) {
                continue;
            }
            $weekly[$day][] = ['start' => $start, 'end' => $end];
        }

        if (!$sawWindowRow) {
            throw new RuntimeException('slot-times-config sheet has no weekday rows.');
        }

        $parsedWindows = 0;
        foreach ($weekly as $dayWindows) {
            $parsedWindows += count($dayWindows);
        }
        if ($parsedWindows === 0) {
            throw new RuntimeException('slot-times-config sheet times could not be parsed. Use HH:MM.');
        }

        return [
            'customer_meeting_minutes' => $meeting,
            'consultant_prep_minutes' => $prep,
            'fallback_interval_minutes' => $fallback,
            'weekly_hours' => $weekly,
        ];
    }

    /**
     * @param array<string, mixed> $settings
     */
    private function requiredInt(array $settings, string $key): int
    {
        if (!array_key_exists($key, $settings) || $settings[$key] === '' || $settings[$key] === null) {
            throw new RuntimeException('slot-times-config is missing setting: ' . $key);
        }

        $value = (int) $settings[$key];
        if ($value <= 0) {
            throw new RuntimeException('slot-times-config setting ' . $key . ' must be a positive number.');
        }

        return $value;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function readCache(): ?array
    {
        if (!is_readable($this->cacheFile)) {
            return null;
        }
        $decoded = json_decode((string) file_get_contents($this->cacheFile), true);
        if (!is_array($decoded) || empty($decoded['at']) || !is_array($decoded['rules'] ?? null)) {
            return null;
        }
        if (time() - (int) $decoded['at'] > $this->ttlSeconds) {
            return null;
        }

        return $decoded['rules'];
    }

    public function clearCache(): void
    {
        if (is_file($this->cacheFile)) {
            @unlink($this->cacheFile);
        }
    }

    /**
     * @param array<string, mixed> $rules
     */
    private function writeCache(array $rules): void
    {
        file_put_contents(
            $this->cacheFile,
            json_encode(['at' => time(), 'rules' => $rules], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
