<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/Http.php';
require_once __DIR__ . '/src/RazorpayService.php';
require_once __DIR__ . '/src/OrderStore.php';
require_once __DIR__ . '/src/SnackbarService.php';

date_default_timezone_set(snackbar_config()['timezone']);

function snackbar_service(): SnackbarService
{
    static $service = null;
    if ($service === null) {
        $service = new SnackbarService(snackbar_config());
    }
    return $service;
}
