<?php

declare(strict_types=1);

/**
 * Appointment booking configuration (single source of truth).
 *
 * Load order:
 *  1. Secrets from includes/secrets.php (Razorpay, Apps Script, SMTP, admin password)
 *  2. Public booking settings (fee, timezone, services)
 *  3. Weekly schedule (hours / Friday off) — local only, never Sheets
 *
 * Google Sheets is used only for: booked rows, disabled slots, Meet-on-book.
 */

function appointment_secrets(): array
{
    static $secrets = null;

    if ($secrets !== null) {
        return $secrets;
    }

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'secrets.php';

    if (!is_readable($path)) {
        throw new RuntimeException(
            'Missing includes/secrets.php. Copy includes/secrets.example.php to includes/secrets.php and fill in the values on the server.'
        );
    }

    $loaded = require $path;

    if (!is_array($loaded)) {
        throw new RuntimeException('includes/secrets.php must return an array.');
    }

    $secrets = $loaded;

    return $secrets;
}

function appointment_secret(string $key, ?string $default = null): string
{
    $secrets = appointment_secrets();

    if (array_key_exists($key, $secrets) && $secrets[$key] !== null && $secrets[$key] !== '') {
        return (string) $secrets[$key];
    }

    if ($default !== null) {
        return $default;
    }

    throw new RuntimeException('Missing required secret: ' . $key);
}

/**
 * Absolute path to runtime JSON storage (bookings + holds only).
 */
function appointment_storage_path(string $file = ''): string
{
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'storage';
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create appointment-form/storage directory.');
    }

    return $file === '' ? $dir : $dir . DIRECTORY_SEPARATOR . ltrim($file, '/\\');
}

function appointment_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $config = [
        // --- 1. Secrets / integrations ---
        'razorpay_key_id' => appointment_secret('razorpay_key_id'),
        'razorpay_key_secret' => appointment_secret('razorpay_key_secret'),
        'apps_script_url' => rtrim(appointment_secret('apps_script_url', ''), '/'),
        'apps_script_secret' => appointment_secret('apps_script_secret', ''),
        'google_sheet_id' => appointment_secret('google_sheet_id'),
        'google_sheet_name' => appointment_secret('google_sheet_name', 'eatrrite-website-appointments'),
        'google_sheet_tab' => appointment_secret('google_sheet_tab', 'Sheet1'),
        'google_disabled_slots_tab' => appointment_secret('google_disabled_slots_tab', 'disabled-slots'),
        'admin_dashboard_password' => appointment_secret('admin_dashboard_password', ''),

        // --- 2. Booking product settings ---
        'amount_rupees' => 800,
        'snackbar_amount_rupees' => 3999,
        'currency' => 'INR',
        'timezone' => 'Asia/Kolkata',
        'booking_days_ahead' => 30,
        'hold_minutes' => 15,
        'business_name' => 'Eat Rrite',
        'business_description' => 'Appointment confirmation',
        'services' => [
            'Nutrition for Weight & Lifestyle Management',
            'Nutrition for Diabetes Management & Reversal',
            'Nutrition for Gut Health',
            'Female Hormone Health Diet Program',
        ],

        // --- 3. Weekly schedule (edit here; empty day = closed) ---
        'slot_schedule' => [
            'customer_meeting_minutes' => 30,
            'consultant_prep_minutes' => 15,
            'fallback_interval_minutes' => 15,
            'weekly_hours' => [
                'monday' => [
                    ['start' => '11:30', 'end' => '14:00'],
                    ['start' => '15:30', 'end' => '17:30'],
                    ['start' => '20:30', 'end' => '21:30'],
                ],
                'tuesday' => [
                    ['start' => '11:30', 'end' => '14:00'],
                    ['start' => '15:30', 'end' => '17:30'],
                    ['start' => '20:30', 'end' => '21:30'],
                ],
                'wednesday' => [
                    ['start' => '11:30', 'end' => '14:00'],
                    ['start' => '15:30', 'end' => '17:30'],
                    ['start' => '20:30', 'end' => '21:30'],
                ],
                'thursday' => [
                    ['start' => '11:30', 'end' => '14:00'],
                    ['start' => '15:30', 'end' => '17:30'],
                    ['start' => '20:30', 'end' => '21:30'],
                ],
                'friday' => [],
                'saturday' => [
                    ['start' => '11:30', 'end' => '14:30'],
                ],
                'sunday' => [
                    ['start' => '11:30', 'end' => '13:00'],
                ],
            ],
        ],
    ];

    return $config;
}

/**
 * Normalized slot_times payload for SlotService (from config only).
 *
 * @return array{
 *   customer_meeting_minutes:int,
 *   consultant_prep_minutes:int,
 *   fallback_interval_minutes:int,
 *   weekly_hours:array<string, list<array{start:string,end:string}>>,
 *   source:string
 * }
 */
function appointment_slot_times(?array $config = null): array
{
    $config ??= appointment_config();
    $schedule = $config['slot_schedule'] ?? null;

    if (!is_array($schedule) || $schedule === []) {
        throw new RuntimeException('Missing slot_schedule in appointment-form/config.php.');
    }

    $requiredPositive = static function (array $settings, string $key): int {
        if (!array_key_exists($key, $settings) || $settings[$key] === '' || $settings[$key] === null) {
            throw new RuntimeException('slot_schedule is missing: ' . $key);
        }
        $value = (int) $settings[$key];
        if ($value <= 0) {
            throw new RuntimeException('slot_schedule.' . $key . ' must be a positive number.');
        }

        return $value;
    };

    $weeklyIn = $schedule['weekly_hours'] ?? null;
    if (!is_array($weeklyIn)) {
        throw new RuntimeException('slot_schedule.weekly_hours must be an array in appointment-form/config.php.');
    }

    $weekly = [
        'monday' => [],
        'tuesday' => [],
        'wednesday' => [],
        'thursday' => [],
        'friday' => [],
        'saturday' => [],
        'sunday' => [],
    ];

    foreach ($weekly as $day => $_) {
        $windows = $weeklyIn[$day] ?? [];
        if (!is_array($windows)) {
            throw new RuntimeException('slot_schedule.weekly_hours.' . $day . ' must be a list.');
        }
        foreach ($windows as $window) {
            if (!is_array($window)) {
                continue;
            }
            $start = trim((string) ($window['start'] ?? ''));
            $end = trim((string) ($window['end'] ?? ''));
            if ($start === '' || $end === '') {
                continue;
            }
            $weekly[$day][] = ['start' => $start, 'end' => $end];
        }
    }

    return [
        'customer_meeting_minutes' => $requiredPositive($schedule, 'customer_meeting_minutes'),
        'consultant_prep_minutes' => $requiredPositive($schedule, 'consultant_prep_minutes'),
        'fallback_interval_minutes' => $requiredPositive($schedule, 'fallback_interval_minutes'),
        'weekly_hours' => $weekly,
        'source' => 'config',
    ];
}
