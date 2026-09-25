<?php

declare(strict_types=1);

require_once __DIR__ . '/appointment-form/bootstrap.php';

$bookingSession = appointment_verified_booking();
$id = (string) ($bookingSession['verified']['appointment_id'] ?? '');
header('Location: ' . ($id !== ''
    ? 'appointment-confirmed.php?appointmentId=' . rawurlencode($id)
    : 'appointment.php'));
exit;
