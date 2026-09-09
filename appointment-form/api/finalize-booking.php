<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

appointment_require_post();
appointment_assert_csrf();

set_time_limit(180);

try {
    $result = appointment_service()->finalizeBooking(appointment_json_input());
    appointment_json_ok($result);
} catch (InvalidArgumentException $e) {
    appointment_json_error($e->getMessage(), 400);
} catch (Throwable $e) {
    appointment_json_error($e->getMessage(), 500);
}
