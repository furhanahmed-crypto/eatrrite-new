<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/Http.php';
require_once __DIR__ . '/src/SlotService.php';
require_once __DIR__ . '/src/HoldService.php';
require_once __DIR__ . '/src/RazorpayService.php';
require_once __DIR__ . '/src/GoogleAppsScriptClient.php';
require_once __DIR__ . '/src/DisabledSlotsStore.php';
require_once __DIR__ . '/src/BookingStore.php';
require_once __DIR__ . '/src/AppointmentService.php';

date_default_timezone_set(appointment_config()['timezone']);

/**
 * Runtime config.
 * Schedule is always local. Disabled slots load from Apps Script only when needed.
 */
function appointment_runtime_config(bool $withDisabledSlots = false): array
{
    static $local = null;
    static $withDisabled = null;

    if ($local === null) {
        $config = appointment_config();
        $config['slot_times'] = appointment_slot_times($config);
        $config['disabled_slots'] = [];
        $local = $config;
    }

    if (!$withDisabledSlots) {
        return $local;
    }

    if ($withDisabled === null) {
        $config = $local;
        $config['disabled_slots'] = (new DisabledSlotsStore($config))->all();
        $withDisabled = $config;
    }

    return $withDisabled;
}

function appointment_service(): AppointmentService
{
    static $service = null;
    if ($service === null) {
        $service = new AppointmentService(appointment_runtime_config(true));
    }

    return $service;
}
