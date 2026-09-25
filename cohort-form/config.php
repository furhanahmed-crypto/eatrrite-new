<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/secrets-loader.php';

function cohort_secrets(): array
{
    return app_secrets();
}

function cohort_secret(string $key, ?string $default = null): string
{
    return app_secret($key, $default);
}

function cohort_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'razorpay_key_id' => cohort_secret('razorpay_key_id'),
        'razorpay_key_secret' => cohort_secret('razorpay_key_secret'),
        'consultation_rupees' => 800,
        'monthly_rupees' => 20000,
        'spots' => 10,
        'timezone' => 'Asia/Kolkata',
        'currency' => 'INR',
        'business_name' => 'Eat Rrite',
        'business_description' => 'Cohort consultation',
        'product_name' => 'Lifestyle Reversal Cohort',
    ];

    return $config;
}

function cohort_now(): DateTimeImmutable
{
    return new DateTimeImmutable('now', new DateTimeZone(cohort_config()['timezone']));
}

function cohort_month(?DateTimeImmutable $now = null): string
{
    return ($now ?? cohort_now())->format('Y-m');
}
