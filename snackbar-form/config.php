<?php

declare(strict_types=1);

/**
 * Snackbar checkout — unit price and Razorpay settings.
 */

function snackbar_secrets(): array
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

function snackbar_secret(string $key, ?string $default = null): string
{
    $secrets = snackbar_secrets();
    if (array_key_exists($key, $secrets) && $secrets[$key] !== null && $secrets[$key] !== '') {
        return (string) $secrets[$key];
    }
    if ($default !== null) {
        return $default;
    }
    throw new RuntimeException('Missing required secret: ' . $key);
}

function snackbar_storage_path(string $file = ''): string
{
    $dir = __DIR__ . DIRECTORY_SEPARATOR . 'storage';
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create snackbar-form/storage.');
    }
    return $file === '' ? $dir : $dir . DIRECTORY_SEPARATOR . ltrim($file, '/\\');
}

function snackbar_config(): array
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [
        'razorpay_key_id' => snackbar_secret('razorpay_key_id'),
        'razorpay_key_secret' => snackbar_secret('razorpay_key_secret'),
        'amount_rupees' => 3999,
        'currency' => 'INR',
        'min_quantity' => 1,
        'max_quantity' => 20,
        'timezone' => 'Asia/Kolkata',
        'business_name' => 'Eat Rrite',
        'business_description' => 'Snackbar',
        'product_name' => 'Eat Rrite Snackbar',
    ];

    return $config;
}
