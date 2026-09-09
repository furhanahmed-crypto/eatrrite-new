<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

appointment_require_post();
appointment_assert_csrf();

try {
    $order = appointment_service()->createOrder(appointment_json_input());
    appointment_json_ok($order);
} catch (InvalidArgumentException $e) {
    appointment_json_error($e->getMessage(), 409);
} catch (Throwable $e) {
    appointment_json_error($e->getMessage(), 500);
}
