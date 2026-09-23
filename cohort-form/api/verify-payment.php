<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

cohort_require_post();
cohort_assert_csrf();

try {
    $input = cohort_json_input();
    $result = cohort_service()->verifyPayment($input);
    cohort_store_application($input, $result);
    cohort_json_ok($result);
} catch (InvalidArgumentException $e) {
    cohort_json_fail($e, 400);
} catch (Throwable $e) {
    cohort_json_fail($e, 500);
}
