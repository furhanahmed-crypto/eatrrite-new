<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/bootstrap.php';

if (!admin_dashboard_authed()) {
    appointment_json_error('Please sign in to the dashboard first.', 401);
}

appointment_require_post();
appointment_assert_csrf();

try {
    $input = appointment_json_input();
    $date = trim((string) ($input['date'] ?? ''));
    $time = trim((string) ($input['time'] ?? ''));
    $hidden = !empty($input['hidden']);

    $config = appointment_runtime_config();
    $slots = new SlotService($config);

    $slots->parseDate($date);
    $normalized = $slots->normalizeTime($time);
    if ($normalized === null || !in_array($normalized, $slots->offeredTimesForDate($date), true)) {
        appointment_json_error('That time is not a slot on this day.', 400);
    }

    (new DisabledSlotsStore($config))->set($date, $normalized, $hidden);

    appointment_json_ok([
        'date' => $date,
        'time' => $normalized,
        'hidden' => $hidden,
    ]);
} catch (InvalidArgumentException $e) {
    appointment_json_error($e->getMessage(), 400);
} catch (Throwable $e) {
    appointment_json_error($e->getMessage(), 500);
}
