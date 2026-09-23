<?php

declare(strict_types=1);

/**
 * Cohort applications — fees and Razorpay settings.
 */

function cohort_secrets(): array
{
    static $secrets = null;
    if ($secrets !== null) {
        return $secrets;
    }

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'secrets.php';
    if (!is_readable($path)) {
        throw new RuntimeException(
            'Missing includes/secrets.php. Copy includes/secrets.example.php and fill Razorpay keys.'
        );
    }

    $loaded = require $path;
    if (!is_array($loaded)) {
        throw new RuntimeException('includes/secrets.php must return an array.');
    }

    $secrets = $loaded;
    return $secrets;
}

function cohort_secret(string $key, ?string $default = null): string
{
    $secrets = cohort_secrets();
    if (array_key_exists($key, $secrets) && $secrets[$key] !== null && $secrets[$key] !== '') {
        return (string) $secrets[$key];
    }
    if ($default !== null) {
        return $default;
    }
    throw new RuntimeException('Missing required secret: ' . $key);
}

function cohort_storage_path(string $file = ''): string
{
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'storage';
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create cohort-form/storage.');
    }
    return $file === '' ? $dir : $dir . DIRECTORY_SEPARATOR . ltrim($file, '/\\');
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
    $now ??= cohort_now();
    return $now->format('Y-m');
}
