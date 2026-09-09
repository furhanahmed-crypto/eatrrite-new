<?php

declare(strict_types=1);

final class DisabledSlotsStore
{
    private GoogleAppsScriptClient $sheet;
    private string $cacheFile;
    private int $ttlSeconds = 30;

    public function __construct(array $config, ?GoogleAppsScriptClient $sheet = null)
    {
        $this->sheet = $sheet ?? new GoogleAppsScriptClient($config);
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create appointment data directory.');
        }
        $this->cacheFile = $dir . DIRECTORY_SEPARATOR . 'disabled-slots-cache.json';
    }

    /**
     * @return list<array{date:string,time:string}>
     */
    public function all(): array
    {
        $cached = $this->readCache();
        if (is_array($cached)) {
            return $cached;
        }

        try {
            $rows = $this->sheet->listDisabledSlots();
        } catch (Throwable) {
            return [];
        }

        $this->writeCache($rows);

        return $rows;
    }

    public function set(string $date, string $time, bool $hidden): void
    {
        $this->sheet->setDisabledSlot($date, $time, $hidden);
        $this->clearCache();
    }

    public function clearCache(): void
    {
        if (is_file($this->cacheFile)) {
            @unlink($this->cacheFile);
        }
    }

    /**
     * @return list<array{date:string,time:string}>|null
     */
    private function readCache(): ?array
    {
        if (!is_readable($this->cacheFile)) {
            return null;
        }
        $decoded = json_decode((string) file_get_contents($this->cacheFile), true);
        if (!is_array($decoded) || empty($decoded['at']) || !is_array($decoded['rows'] ?? null)) {
            return null;
        }
        if (time() - (int) $decoded['at'] > $this->ttlSeconds) {
            return null;
        }

        return $decoded['rows'];
    }

    /**
     * @param list<array{date:string,time:string}> $rows
     */
    private function writeCache(array $rows): void
    {
        file_put_contents(
            $this->cacheFile,
            json_encode(['at' => time(), 'rows' => $rows], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
