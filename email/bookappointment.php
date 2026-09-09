<?php

declare(strict_types=1);

require_once __DIR__ . '/AppointmentEmails.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ../appointment.php');
    exit;
}

try {
    send_admin_booking_email([
        'name' => trim((string) ($_POST['name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'phone' => trim((string) ($_POST['mobilenumber'] ?? '')),
        'service' => trim((string) ($_POST['programname'] ?? '')),
        'display_date' => trim((string) ($_POST['date'] ?? '')),
        'display_time' => trim((string) ($_POST['time'] ?? '')),
    ]);

    header('Location: ../appointment.php?appointmentsuccess=1#subscribe');
} catch (Throwable $e) {
    header('Location: ../appointment.php?appointmentfail=1#subscribe');
}
