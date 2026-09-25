<?php

declare(strict_types=1);

/**
 * Merge gitignored local files. db.local.php wins over secrets.php
 * for any key that is present and non-empty.
 *
 * @return array<string, mixed>
 */
function app_secrets(): array
{
    static $secrets = null;
    if ($secrets !== null) {
        return $secrets;
    }

    $merged = [];
    foreach (['secrets.php', 'db.local.php'] as $file) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
        if (!is_readable($path)) {
            continue;
        }
        $loaded = require $path;
        if (!is_array($loaded)) {
            throw new RuntimeException($file . ' must return an array.');
        }
        $merged = app_secrets_overlay($merged, $loaded);
    }

    if ($merged === []) {
        throw new RuntimeException(
            'Missing includes/db.local.php (or secrets.php). Copy the .example file and fill values.'
        );
    }

    $secrets = $merged;
    return $secrets;
}

function app_secret(string $key, ?string $default = null): string
{
    $secrets = app_secrets();
    $value = $secrets[$key] ?? null;
    if ($value !== null && $value !== '') {
        return (string) $value;
    }
    if ($default !== null) {
        return $default;
    }
    throw new RuntimeException('Missing required secret: ' . $key);
}

/**
 * @param array<string, mixed> $base
 * @param array<string, mixed> $overlay
 * @return array<string, mixed>
 */
function app_secrets_overlay(array $base, array $overlay): array
{
    foreach ($overlay as $key => $value) {
        if (is_array($value) && is_array($base[$key] ?? null)) {
            $base[$key] = app_secrets_overlay($base[$key], $value);
            continue;
        }
        if ($value !== null && $value !== '') {
            $base[$key] = $value;
        }
    }
    return $base;
}
