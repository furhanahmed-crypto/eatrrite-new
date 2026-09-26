<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/secrets-loader.php';
require_once dirname(__DIR__) . '/constants/schedule.php';
require_once dirname(__DIR__) . '/constants/slot-times.php';

function appointment_secrets(): array
{
    return app_secrets();
}

function appointment_secret(string $key, ?string $default = null): string
{
    return app_secret($key, $default);
}

function appointment_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $secrets = appointment_secrets();
    $config = [
        'razorpay_key_id' => appointment_secret('razorpay_key_id'),
        'razorpay_key_secret' => appointment_secret('razorpay_key_secret'),
        'apps_script_url' => rtrim((string) ($secrets['apps_script_url'] ?? ''), '/'),
        'apps_script_secret' => (string) ($secrets['apps_script_secret'] ?? ''),
        'google_sheet_id' => (string) ($secrets['google_sheet_id'] ?? ''),
        'google_sheet_name' => (string) ($secrets['google_sheet_name'] ?? 'eatrrite-website-appointments'),
        'google_sheet_tab' => (string) ($secrets['google_sheet_tab'] ?? 'Sheet1'),
        'google_disabled_slots_tab' => (string) ($secrets['google_disabled_slots_tab'] ?? 'disabled-slots'),
        'admin_dashboard_password' => (string) (
            $secrets['admin_password'] ?? $secrets['admin_dashboard_password'] ?? ''
        ),
        'public_base_url' => rtrim((string) ($secrets['public_base_url'] ?? ''), '/'),
        'cron_secret' => (string) ($secrets['cron_secret'] ?? ''),
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
        'cohort_service' => 'Consultation — program recommended on the call',
        'slot_schedule' => appointment_schedule_settings(),
    ];

    return $config;
}

function appointment_service_label(string $service): string
{
    $current = trim((string) (appointment_config()['cohort_service'] ?? ''));
    $legacy = [
        'Consultation — to be recommended',
        'Consultation - to be recommended',
        'Consultation – to be recommended',
    ];
    if ($current !== '' && in_array($service, $legacy, true)) {
        return $current;
    }

    return $service;
}
