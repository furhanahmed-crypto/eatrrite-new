<?php

declare(strict_types=1);

/**
 * Appointment booking configuration.
 * Secrets come from includes/secrets.php (gitignored — keep only on the server).
 * Never send KEY_SECRET to the browser.
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

function appointment_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'razorpay_key_id' => appointment_secret('razorpay_key_id'),
        'razorpay_key_secret' => appointment_secret('razorpay_key_secret'),
        'google_sheet_id' => appointment_secret('google_sheet_id'),
        'google_sheet_name' => appointment_secret('google_sheet_name', 'eatrrite-website-appointments'),
        'google_sheet_tab' => appointment_secret('google_sheet_tab', 'Sheet1'),
        'google_slot_times_tab' => appointment_secret('google_slot_times_tab', 'slot-times-config'),
        'google_disabled_slots_tab' => appointment_secret('google_disabled_slots_tab', 'disabled-slots'),
        'apps_script_url' => rtrim(appointment_secret('apps_script_url', ''), '/'),
        'apps_script_secret' => appointment_secret('apps_script_secret', ''),

        'amount_rupees' => 800,
        'currency' => 'INR',
        'timezone' => 'Asia/Kolkata',
        'booking_days_ahead' => 30,
        'hold_minutes' => 15,
        'admin_dashboard_password' => appointment_secret('admin_dashboard_password', ''),

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
