<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

cohort_require_post();
cohort_assert_csrf();

try {
    cohort_json_ok(cohort_service()->createOrder(cohort_json_input()));
} catch (InvalidArgumentException $e) {
    cohort_json_fail($e, 400);
} catch (Throwable $e) {
    cohort_json_fail($e, 500);
}
