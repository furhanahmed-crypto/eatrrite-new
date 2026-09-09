<?php

declare(strict_types=1);

/**
 * Appointment booking configuration.
 * Secrets come from the project-root .env file. Never send KEY_SECRET to the browser.
 */
function appointment_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $env = appointment_load_env(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

    $config = [
        'razorpay_key_id' => appointment_env($env, 'RAZORPAY_KEY_ID'),
        'razorpay_key_secret' => appointment_env($env, 'RAZORPAY_KEY_SECRET'),
        'google_sheet_id' => appointment_env($env, 'GOOGLE_SHEET_ID'),
        'google_sheet_name' => appointment_env($env, 'GOOGLE_SHEET_NAME', 'eatrrite-website-appointments'),
        'google_sheet_tab' => appointment_env($env, 'GOOGLE_SHEET_TAB_NAME', 'Sheet1'),
        'google_slot_times_tab' => appointment_env($env, 'GOOGLE_SLOT_TIMES_TAB', 'slot-times-config'),
        'google_disabled_slots_tab' => appointment_env($env, 'GOOGLE_DISABLED_SLOTS_TAB', 'disabled-slots'),
        'apps_script_url' => rtrim(appointment_env($env, 'GOOGLE_APPS_SCRIPT_WEBAPP_URL', ''), '/'),
        'apps_script_secret' => appointment_env($env, 'GOOGLE_APPS_SCRIPT_SECRET', ''),

        'amount_rupees' => 800,
        'currency' => 'INR',
        'timezone' => 'Asia/Kolkata',
        'booking_days_ahead' => 30,
        'hold_minutes' => 15,
        'admin_dashboard_password' => appointment_env($env, 'ADMIN_DASHBOARD_PASSWORD', ''),

        'business_name' => 'Eat Rrite',
        'business_description' => 'Appointment confirmation',

        'services' => [
            'Nutrition for Weight & Lifestyle Management',
            'Nutrition for Diabetes Management & Reversal',
            'Nutrition for Gut Health',
            'Female Hormone Health Diet Program',
        ],
    ];

    return $config;
}

function appointment_load_env(string $path): array
{
    if (!is_readable($path)) {
        throw new RuntimeException('Missing .env file at project root.');
    }

    $vars = [];

    foreach (file($path, FILE_IGNORE_NEW_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $vars[trim($key)] = trim($value);
    }

    return $vars;
}

function appointment_env(array $env, string $key, ?string $default = null): string
{
    if (array_key_exists($key, $env) && $env[$key] !== '') {
        return $env[$key];
    }

    if ($default !== null) {
        return $default;
    }

    throw new RuntimeException('Missing required environment variable: ' . $key);
}
