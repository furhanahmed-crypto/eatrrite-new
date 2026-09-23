<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

snackbar_require_post();
snackbar_assert_csrf();

try {
    $input = snackbar_json_input();
    $result = snackbar_service()->verifyPayment($input);
    snackbar_store_order($input, $result);
    snackbar_json_ok($result);
} catch (InvalidArgumentException $e) {
    snackbar_json_fail($e, 400);
} catch (Throwable $e) {
    snackbar_json_fail($e, 500);
}
