<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/src/QuestionnaireReminders.php';

$isCli = PHP_SAPI === 'cli';
$dryRun = $isCli && in_array('--dry', $argv ?? [], true);

if (!$isCli) {
    $config = appointment_config();
    $expected = (string) ($config['cron_secret'] ?? '');
    if ($expected === '') {
        $expected = (string) ($config['apps_script_secret'] ?? '');
    }
    $given = (string) ($_GET['key'] ?? '');
    if ($expected === '' || !hash_equals($expected, $given)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Forbidden']);
        exit;
    }
}

try {
    $result = (new QuestionnaireReminders())->run($dryRun);
    if ($isCli) {
        echo ($dryRun ? 'dry-run ' : '') . 'checked=' . $result['checked'] . ' sent=' . $result['sent'] . PHP_EOL;
        exit(0);
    }
    appointment_json_ok($result);
} catch (Throwable $e) {
    error_log('[reminders] ' . $e->getMessage());
    if ($isCli) {
        fwrite(STDERR, 'Reminder cron failed.' . PHP_EOL);
        exit(1);
    }
    appointment_json_fail($e, 500);
}
