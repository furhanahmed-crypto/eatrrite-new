<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

appointment_require_post();
appointment_assert_csrf();

try {
    $input = appointment_json_input();
    $appointmentId = trim((string) ($input['appointment_id'] ?? ''));
    if ($appointmentId === '') {
        appointment_json_error('Appointment id is required.', 400);
    }

    appointment_json_ok(appointment_service()->generateMeet($appointmentId));
} catch (InvalidArgumentException $e) {
    appointment_json_fail($e, 400);
} catch (Throwable $e) {
    appointment_json_fail($e, 500);
}
