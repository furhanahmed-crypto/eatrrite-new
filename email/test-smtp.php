<?php

declare(strict_types=1);

/**
 * Run from project root: php email/test-smtp.php
 * Sends a test email using settings from .env / AppointmentEmails.php.
 */
require_once __DIR__ . '/AppointmentEmails.php';

$settings = appointment_mail_settings();
$to = $settings['admin_email'];

echo "SMTP host: {$settings['host']}:{$settings['port']}\n";
echo "Username:  {$settings['username']}\n";
echo "Admin to:  {$to}\n";

try {
    appointment_send_email(
        $to,
        $settings['admin_name'],
        'Eat Rrite SMTP test',
        '<p>If you received this, SMTP is working.</p>'
    );
    echo "OK — test email sent to {$to}\n";
} catch (Throwable $e) {
    echo "FAILED — {$e->getMessage()}\n";
    echo "\nFix: generate a new Gmail App Password for {$settings['username']}\n";
    echo "     Google Account → Security → 2-Step Verification → App passwords\n";
    echo "     Then add MAIL_PASSWORD=... to your .env file.\n";
    exit(1);
}
