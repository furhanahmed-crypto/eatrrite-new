<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/Http.php';
require_once __DIR__ . '/src/SlotClock.php';
require_once __DIR__ . '/src/SlotHours.php';
require_once __DIR__ . '/src/SlotGrid.php';
require_once __DIR__ . '/src/SlotRules.php';
require_once __DIR__ . '/src/SlotService.php';
require_once __DIR__ . '/src/HoldService.php';
require_once __DIR__ . '/src/RazorpayService.php';
require_once __DIR__ . '/src/GoogleAppsScriptClient.php';
require_once __DIR__ . '/src/DisabledSlotsStore.php';
require_once __DIR__ . '/src/BookingStore.php';
require_once __DIR__ . '/src/AppointmentValidator.php';
require_once __DIR__ . '/src/AppointmentResponses.php';
require_once __DIR__ . '/src/AppointmentFinalize.php';
require_once __DIR__ . '/src/AppointmentPayment.php';
require_once __DIR__ . '/src/AppointmentCheckout.php';
require_once __DIR__ . '/src/QuestionnaireValidator.php';
require_once __DIR__ . '/src/AppointmentService.php';

date_default_timezone_set(appointment_config()['timezone']);

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
