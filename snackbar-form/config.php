<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/secrets-loader.php';

function snackbar_secrets(): array
{
    return app_secrets();
}

function snackbar_secret(string $key, ?string $default = null): string
{
    return app_secret($key, $default);
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
