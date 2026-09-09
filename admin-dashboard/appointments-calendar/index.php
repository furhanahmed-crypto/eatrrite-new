<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

admin_dashboard_require();

$config = appointment_runtime_config();
$slots = new SlotService($config);
$sheet = new GoogleAppsScriptClient($config);
$feed = new AppointmentFeed($slots, $sheet);

$requestedView = (string) ($_GET['view'] ?? '');
$view = in_array($requestedView, ['month', 'day'], true) ? $requestedView : 'month';

$today = $slots->today()->setTime(0, 0, 0);
$dateParam = trim((string) ($_GET['date'] ?? ''));
try {
    $selected = $dateParam !== '' ? $slots->parseDate($dateParam) : $today;
} catch (InvalidArgumentException) {
    $selected = $today;
}

$canonicalDate = $selected->format('Y-m-d');
if ($requestedView !== $view || $dateParam !== $canonicalDate) {
    header('Location: /admin-dashboard/appointments-calendar/?' . http_build_query([
        'view' => $view,
        'date' => $canonicalDate,
    ]), true, 302);
    exit;
}

$month = $selected->modify('first day of this month')->setTime(0, 0, 0);
$error = '';
$appointments = [];

try {
    if (!$sheet->isConfigured()) {
        $error = 'Google Sheet is not configured, so bookings cannot be loaded yet.';
    } else {
        $appointments = $feed->all();
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

$byDate = CalendarPresenter::groupByDate($appointments);
$monthCells = CalendarPresenter::monthCells($month, $selected, $today, $byDate);
$dayEvents = $byDate[$selected->format('Y-m-d')] ?? [];
$dayLayout = CalendarPresenter::dayLayout($dayEvents, $slots, $selected);
$dayWindows = CalendarPresenter::windowsFor($slots, $selected);

$counts = [];
foreach ($appointments as $row) {
    $service = (string) ($row['service'] ?: 'Other');
    if (!isset($counts[$service])) {
        $counts[$service] = ['label' => $service, 'count' => 0, 'color' => $row['color']];
    }
    $counts[$service]['count']++;
}
uasort($counts, static fn (array $a, array $b): int => $b['count'] <=> $a['count']);

$prevMonth = $month->modify('-1 month');
$nextMonth = $month->modify('+1 month');
$prevDay = $selected->modify('-1 day');
$nextDay = $selected->modify('+1 day');

$query = static function (string $nextView, DateTimeImmutable $date): string {
    return '?' . http_build_query([
        'view' => $nextView,
        'date' => $date->format('Y-m-d'),
    ]);
};

$title = 'Appointments calendar';
$assetBase = '/admin-dashboard/appointments-calendar/assets';
$showLogout = admin_dashboard_password() !== '';

require dirname(__DIR__) . '/layout-start.php';
require __DIR__ . '/components/shell.php';
require dirname(__DIR__) . '/layout-end.php';
