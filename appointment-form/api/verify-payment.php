<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

appointment_require_post();
appointment_assert_csrf();

try {
    $input = appointment_json_input();
    $result = appointment_service()->verifyPayment($input);
    appointment_store_verified_booking($input, $result);
    $id = (string) ($result['appointment_id'] ?? '');
    $result['redirect'] = $id !== ''
        ? 'appointment-confirmed.php?appointmentId=' . rawurlencode($id)
        : 'appointment-confirmed.php';
    appointment_json_ok($result);
} catch (InvalidArgumentException $e) {
    appointment_json_fail($e, 400);
} catch (Throwable $e) {
    appointment_json_fail($e, 500);
}
