<?php

declare(strict_types=1);

require_once __DIR__ . '/secrets-loader.php';

/**
 * Shared MySQL PDO (Hostinger phpMyAdmin / local remote MySQL).
 */
function app_db_config(): array
{
    $secrets = app_secrets();
    foreach (['host', 'name', 'user'] as $key) {
        if (empty($secrets[$key])) {
            throw new RuntimeException(
                'MySQL ' . $key . ' is missing in includes/db.local.php.'
            );
        }
    }

    return [
        'host' => (string) $secrets['host'],
        'port' => (int) ($secrets['port'] ?? 3306),
        'name' => (string) $secrets['name'],
        'user' => (string) $secrets['user'],
        'pass' => (string) ($secrets['pass'] ?? ''),
        'charset' => (string) ($secrets['charset'] ?? 'utf8mb4'),
        'debug' => (bool) ($secrets['debug'] ?? false),
    ];
}

function app_pdo(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = app_db_config();
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['name'],
        $config['charset']
    );

    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function app_id(string $prefix = ''): string
{
    return $prefix . bin2hex(random_bytes(12));
}
