<?php

declare(strict_types=1);

function cohort_json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);
    if (!is_array($data)) {
        cohort_json_error('Invalid JSON body.', 400);
    }
    return $data;
}

function cohort_json_ok(array $payload, int $status = 200): void
{
    cohort_json_response(['ok' => true] + $payload, $status);
}

function cohort_json_error(string $message, int $status = 400): void
{
    cohort_json_response(['ok' => false, 'error' => $message], $status);
}

function cohort_json_fail(Throwable $e, int $status = 500): void
{
    error_log('[cohort] ' . $e->getMessage());
    $public = $e instanceof InvalidArgumentException && $status < 500
        ? $e->getMessage()
        : 'A technical issue occurred. Please retry.';
    cohort_json_error($public, $status);
}

function cohort_json_response(array $payload, int $status): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function cohort_csrf_token(): string
{
    if (empty($_SESSION['er_cohort_csrf']) || !is_string($_SESSION['er_cohort_csrf'])) {
        $_SESSION['er_cohort_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['er_cohort_csrf'];
}

function cohort_assert_csrf(): void
{
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || !hash_equals(cohort_csrf_token(), $token)) {
        cohort_json_error('Your session expired. Refresh the page and try again.', 403);
    }
}

function cohort_require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        cohort_json_error('Method not allowed.', 405);
    }
}

function cohort_store_application(array $payment, array $verified): void
{
    $_SESSION['er_cohort_application'] = [
        'payment' => $payment,
        'verified' => $verified,
        'stored_at' => time(),
    ];
}

function cohort_verified_application(): ?array
{
    $data = $_SESSION['er_cohort_application'] ?? null;
    if (!is_array($data) || !is_array($data['verified'] ?? null)) {
        return null;
    }
    if (time() - (int) ($data['stored_at'] ?? 0) > 86400) {
        unset($_SESSION['er_cohort_application']);
        return null;
    }
    return $data;
}
