<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    appointment_json_ok(appointment_service()->availability());
} catch (Throwable $e) {
    appointment_json_error($e->getMessage(), 500);
}
