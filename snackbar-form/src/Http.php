<?php

declare(strict_types=1);

function snackbar_json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);
    if (!is_array($data)) {
        snackbar_json_error('Invalid JSON body.', 400);
    }
    return $data;
}

function snackbar_json_ok(array $payload, int $status = 200): void
{
    snackbar_json_response(['ok' => true] + $payload, $status);
}

function snackbar_json_error(string $message, int $status = 400): void
{
    snackbar_json_response(['ok' => false, 'error' => $message], $status);
}

function snackbar_json_fail(Throwable $e, int $status = 500): void
{
    error_log('[snackbar] ' . $e->getMessage());
    $public = $e instanceof InvalidArgumentException && $status < 500
        ? $e->getMessage()
        : 'A technical issue occurred. Please retry.';
    snackbar_json_error($public, $status);
}

function snackbar_json_response(array $payload, int $status): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function snackbar_csrf_token(): string
{
    if (empty($_SESSION['er_snackbar_csrf']) || !is_string($_SESSION['er_snackbar_csrf'])) {
        $_SESSION['er_snackbar_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['er_snackbar_csrf'];
}

function snackbar_assert_csrf(): void
{
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || !hash_equals(snackbar_csrf_token(), $token)) {
        snackbar_json_error('Your session expired. Refresh the page and try again.', 403);
    }
}

function snackbar_require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        snackbar_json_error('Method not allowed.', 405);
    }
}

function snackbar_store_order(array $payment, array $verified): void
{
    $_SESSION['er_snackbar_order'] = [
        'payment' => $payment,
        'verified' => $verified,
        'stored_at' => time(),
    ];
}

function snackbar_verified_order(): ?array
{
    $data = $_SESSION['er_snackbar_order'] ?? null;
    if (!is_array($data) || !is_array($data['verified'] ?? null)) {
        return null;
    }
    if (time() - (int) ($data['stored_at'] ?? 0) > 86400) {
        unset($_SESSION['er_snackbar_order']);
        return null;
    }
    return $data;
}
