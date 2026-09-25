<?php

declare(strict_types=1);

require_once __DIR__ . '/appointment-form/bootstrap.php';

$appointmentId = trim((string) ($_GET['appointmentId'] ?? ''));
$appointment = $appointmentId !== '' ? (new BookingStore())->findById($appointmentId) : null;
if ($appointment === null) {
    header('Location: appointment.php');
    exit;
}

$pageTitle = 'Appointment Confirmed | Eat Rrite';
$currentPage = 'appointment';
$spec = require __DIR__ . '/constants/questionnaire.php';
$csrf = appointment_csrf_token();
$apiBase = appointment_public_path('api');
$assetBase = appointment_public_path('assets');
$assetVersion = max(
    (int) @filemtime(__DIR__ . '/appointment-form/assets/questionnaire.css'),
    (int) @filemtime(__DIR__ . '/appointment-form/assets/questionnaire.js'),
    (int) @filemtime(__DIR__ . '/appointment-form/assets/appointment.css')
);
$extraCss = [
    'appointment-form/assets/appointment.css?v=' . $assetVersion,
    'appointment-form/assets/questionnaire.css?v=' . $assetVersion,
];

include __DIR__ . '/includes/header.php';
?>
<section class="section appointment-section appointment-section--next">
    <div class="container er-q-page">
        <?php include __DIR__ . '/appointment-form/views/confirmed-left.php'; ?>
        <?php include __DIR__ . '/appointment-form/views/questionnaire-form.php'; ?>
    </div>
</section>
<script src="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/questionnaire.js?v=<?php echo $assetVersion; ?>" defer></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
