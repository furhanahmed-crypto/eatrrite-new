<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

admin_dashboard_require();

$config = appointment_runtime_config(true);
$slots = new SlotService($config);
$feed = new AppointmentFeed($slots, new BookingStore());

$today = $slots->today()->setTime(0, 0, 0);
$dateParam = trim((string) ($_GET['date'] ?? ''));
try {
    $selected = $dateParam !== '' ? $slots->parseDate($dateParam) : $today;
} catch (InvalidArgumentException) {
    $selected = $today;
}

$monthParam = trim((string) ($_GET['month'] ?? ''));
try {
    $month = $monthParam !== ''
        ? $slots->parseDate($monthParam . '-01')
        : $selected->modify('first day of this month');
} catch (InvalidArgumentException) {
    $month = $selected->modify('first day of this month');
}
$month = $month->setTime(0, 0, 0);

$error = '';
$appointments = [];
try {
    $appointments = $feed->all();
} catch (Throwable $e) {
    $error = 'A technical issue occurred. Please retry.';
}

$byDate = CalendarPresenter::groupByDate($appointments);
$monthCells = CalendarPresenter::monthCells($month, $selected, $today, $byDate);
$dayRows = CalendarPresenter::dayRows($byDate[$selected->format('Y-m-d')] ?? [], $slots, $selected);
$prevMonth = $month->modify('-1 month');
$nextMonth = $month->modify('+1 month');

$title = 'Consultations';
$assetBase = '/admin/appointments-calendar/assets';

require dirname(__DIR__) . '/layout-start.php';
require __DIR__ . '/components/shell.php';
require dirname(__DIR__) . '/layout-end.php';
