<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/Http.php';
require_once __DIR__ . '/src/RazorpayService.php';
require_once __DIR__ . '/src/ApplicationStore.php';
require_once __DIR__ . '/src/CohortService.php';

date_default_timezone_set(cohort_config()['timezone']);

function cohort_service(): CohortService
{
    static $service = null;
    if ($service === null) {
        $service = new CohortService(cohort_config());
    }
    return $service;
}
