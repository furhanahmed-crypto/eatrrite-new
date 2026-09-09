<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

/**
 * All appointment booking emails live in this file.
 *
 * - send_paid_booking_emails()  → admin + customer (after Razorpay payment)
 * - send_admin_booking_email()  → team only (legacy forms on program pages)
 */
function appointment_mail_settings(): array
{
    static $settings = null;

    if ($settings !== null) {
        return $settings;
    }

    require_once dirname(__DIR__) . '/appointment-form/config.php';
    $secrets = appointment_secrets();
    $mail = is_array($secrets['mail'] ?? null) ? $secrets['mail'] : [];

    $password = trim((string) ($mail['password'] ?? ''));
    if ($password === '') {
        throw new RuntimeException('Mail password is missing in includes/secrets.php.');
    }

    $settings = [
        'host' => (string) ($mail['host'] ?? 'smtp.gmail.com'),
        'port' => (int) ($mail['port'] ?? 587),
        'encryption' => strtolower(trim((string) ($mail['encryption'] ?? 'tls'))),
        'username' => (string) ($mail['username'] ?? ''),
        'password' => $password,
        'from_email' => (string) ($mail['from_email'] ?? ''),
        'from_name' => (string) ($mail['from_name'] ?? 'Eat Rrite'),
        'admin_email' => (string) ($mail['admin_email'] ?? ''),
        'admin_name' => (string) ($mail['admin_name'] ?? 'Eat Rrite Team'),
    ];

    return $settings;
}

function appointment_render_email(string $templateName, array $data): string
{
    $templatePath = __DIR__ . '/templates/' . $templateName . '.php';
    if (!is_readable($templatePath)) {
        throw new RuntimeException('Email template not found: ' . $templateName);
    }

    $template = $templateName;

    ob_start();
    include __DIR__ . '/templates/layout.php';
    return (string) ob_get_clean();
}

function appointment_send_email(
    string $toEmail,
    string $toName,
    string $subject,
    string $htmlBody,
    ?string $replyToEmail = null,
    ?string $replyToName = null
): void {
    $settings = appointment_mail_settings();
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $settings['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['username'];
        // Gmail app passwords work with or without spaces; strip for consistency.
        $mail->Password = str_replace(' ', '', $settings['password']);

        if ($settings['encryption'] === 'ssl' || $settings['port'] === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port = $settings['port'];
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->setFrom($settings['from_email'], $settings['from_name']);
        $mail->addAddress($toEmail, $toName);

        if ($replyToEmail) {
            $mail->addReplyTo($replyToEmail, $replyToName ?: $replyToEmail);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $logoPath = dirname(__DIR__) . '/assets/images/logo/logo-horizontal.png';
        if (is_readable($logoPath) && str_contains($htmlBody, 'cid:eatrrite-logo')) {
            $mail->addEmbeddedImage($logoPath, 'eatrrite-logo', 'logo-horizontal.png');
        }

        $mail->send();
    } catch (MailException $e) {
        throw new RuntimeException('Email could not be sent: ' . $mail->ErrorInfo);
    }
}

function send_admin_booking_email(array $booking): void
{
    $settings = appointment_mail_settings();

    appointment_send_email(
        $settings['admin_email'],
        $settings['admin_name'],
        'New paid appointment: ' . $booking['name'] . ' · ' . $booking['display_date'],
        appointment_render_email('admin-booking', $booking),
        $booking['email'] ?? null,
        $booking['name'] ?? null
    );
}

function send_customer_booking_email(array $booking): void
{
    appointment_send_email(
        $booking['email'],
        $booking['name'],
        'Your Eat Rrite appointment is confirmed',
        appointment_render_email('customer-booking', $booking)
    );
}

function send_paid_booking_emails(array $booking): void
{
    send_admin_booking_email([
        'name' => $booking['name'],
        'email' => $booking['email'],
        'phone' => $booking['phone'],
        'service' => $booking['service'],
        'display_date' => $booking['display_date'],
        'display_time' => $booking['display_time'],
        'meet_link' => $booking['meet_link'],
        'payment_id' => $booking['payment_id'],
        'booked_at' => $booking['booked_at'],
    ]);

    send_customer_booking_email([
        'name' => $booking['name'],
        'email' => $booking['email'],
        'service' => $booking['service'],
        'display_date' => $booking['display_date'],
        'display_time' => $booking['display_time'],
        'meet_link' => $booking['meet_link'],
    ]);
}
