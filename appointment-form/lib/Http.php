<?php

declare(strict_types=1);

function appointment_json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw !== false && $raw !== '' ? $raw : '[]', true);

    if (!is_array($data)) {
        appointment_json_error('Invalid JSON body.', 400);
    }

    return $data;
}

function appointment_json_ok(array $payload, int $status = 200): void
{
    appointment_json_response(['ok' => true] + $payload, $status);
}

function appointment_json_error(string $message, int $status = 400, array $extra = []): void
{
    appointment_json_response(['ok' => false, 'error' => $message] + $extra, $status);
}

function appointment_json_response(array $payload, int $status): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function appointment_csrf_token(): string
{
    if (empty($_SESSION['er_csrf']) || !is_string($_SESSION['er_csrf'])) {
        $_SESSION['er_csrf'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['er_csrf'];
}

function appointment_assert_csrf(): void
{
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (!is_string($token) || !hash_equals(appointment_csrf_token(), $token)) {
        appointment_json_error('Your session expired. Refresh the page and try again.', 403);
    }
}

function appointment_require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        appointment_json_error('Method not allowed.', 405);
    }
}

function appointment_public_path(string $relative): string
{
    return 'appointment-form/' . ltrim($relative, '/');
}

function appointment_thank_you_url(): string
{
    return 'appointment-thank-you.php';
}

/**
 * @param array<string, string> $payment
 * @param array<string, mixed> $verified
 */
function appointment_store_verified_booking(array $payment, array $verified): void
{
    $_SESSION['er_verified_booking'] = [
        'payment' => [
            'razorpay_order_id' => (string) ($payment['razorpay_order_id'] ?? ''),
            'razorpay_payment_id' => (string) ($payment['razorpay_payment_id'] ?? ''),
            'razorpay_signature' => (string) ($payment['razorpay_signature'] ?? ''),
        ],
        'verified' => $verified,
        'stored_at' => time(),
    ];
}

/**
 * @return array{payment:array<string, string>, verified:array<string, mixed>, stored_at:int}|null
 */
function appointment_verified_booking(): ?array
{
    $data = $_SESSION['er_verified_booking'] ?? null;
    if (!is_array($data) || !is_array($data['payment'] ?? null) || !is_array($data['verified'] ?? null)) {
        return null;
    }

    if (time() - (int) ($data['stored_at'] ?? 0) > 86400) {
        unset($_SESSION['er_verified_booking']);

        return null;
    }

    return $data;
}
