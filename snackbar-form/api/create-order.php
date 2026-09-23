<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

snackbar_require_post();
snackbar_assert_csrf();

try {
    snackbar_json_ok(snackbar_service()->createOrder(snackbar_json_input()));
} catch (InvalidArgumentException $e) {
    snackbar_json_fail($e, 400);
} catch (Throwable $e) {
    snackbar_json_fail($e, 500);
}
