<?php

declare(strict_types=1);

require_once __DIR__ . '/appointment-form/bootstrap.php';

$bookingSession = appointment_verified_booking();
if ($bookingSession === null) {
    header('Location: appointment.php');
    exit;
}

$pageTitle = 'Appointment Confirmed | Eat Rrite';
$currentPage = 'appointment';
$bannerTitle = 'Appointment Confirmed';

$csrf = appointment_csrf_token();
$assetBase = appointment_public_path('assets');
$apiBase = appointment_public_path('api');
$verified = $bookingSession['verified'];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section appointment-section">
    <div class="container">
        <div class="appointment-thankyou-card">
            <div
                id="er-appointment-thankyou"
                class="er-form"
                data-api="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>"
                data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
            >
                <?php include __DIR__ . '/appointment-form/success-panel.php'; ?>
            </div>
        </div>

        <div class="appointment-next-steps">
            <h2 class="section-title">What happens next?</h2>
            <p>Your payment has been received and your consultation slot is reserved with Eat Rrite. We are creating your Google Meet link and adding your appointment to our schedule.</p>
            <p>You will receive a confirmation email with your appointment details and Meet link. For any questions, reach us at
                <a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a>
                or call <a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a>.
            </p>
            <a class="btn btn-primary" href="index.php">Back to Home</a>
        </div>
    </div>
</section>

<script type="application/json" id="er-booking-data"><?php
    echo json_encode([
        'payment' => $bookingSession['payment'],
        'verified' => $verified,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?></script>
<link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/appointment.css">
<script src="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/appointment-thankyou.js" defer></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
