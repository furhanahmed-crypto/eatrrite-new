<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!admin_dashboard_authed()) {
    appointment_json_error('Please sign in to the dashboard first.', 401);
}

appointment_require_post();
appointment_assert_csrf();

try {
    $input = appointment_json_input();
    $date = trim((string) ($input['date'] ?? ''));
    $time = trim((string) ($input['time'] ?? ''));
    $phone = trim((string) ($input['phone'] ?? ''));
    $name = trim((string) ($input['name'] ?? ''));

    if ($date === '' || $time === '') {
        appointment_json_error('Date and time are required.', 400);
    }

    $cancelled = (new BookingStore())->deleteBySlot($date, $time, $phone, $name);
    appointment_json_ok(['cancelled' => true, 'id' => (string) ($cancelled['id'] ?? '')]);
} catch (RuntimeException $e) {
    appointment_json_error($e->getMessage(), 404);
} catch (Throwable $e) {
    appointment_json_error($e->getMessage(), 500);
}
