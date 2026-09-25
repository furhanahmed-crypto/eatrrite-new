<?php

declare(strict_types=1);

final class SlotClock
{
    public static function parse(string $time, DateTimeZone $tz): ?string
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

        $formats = ['H:i', 'G:i', 'H:i:s', 'G:i:s', 'h:i A', 'g:i A', 'h:i a', 'g:i a'];
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
            return (new DateTimeImmutable($time, $tz))->format('H:i');
        } catch (Exception) {
            return null;
        }
    }

    public static function toMinutes(string $time, DateTimeZone $tz): ?int
    {
        $normalized = self::parse($time, $tz);
        if ($normalized === null) {
            return null;
        }
        [$hour, $minute] = array_map('intval', explode(':', $normalized));
        return ($hour * 60) + $minute;
    }

    public static function formatMinutes(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
