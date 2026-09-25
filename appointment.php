<?php
require_once __DIR__ . '/appointment-form/bootstrap.php';

$pageTitle = 'Book Appointment';
$pageDescription = 'Book a consultation with Eat Rrite. Confirm your slot with a small fee, complete the questionnaire, then receive your Google Meet link.';
$currentPage = 'appointment';
$bannerTitle = 'Book Appointment';
$inner = require __DIR__ . '/constants/inner.php';
$copy = $inner['appointment'];
$amount = (int) ($site['amount_rupees'] ?? 800);
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section appointment-section appointment-section--next">
    <div class="container appointment-layout appointment-layout--next">
        <div class="appointment-copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($copy['pill']); ?></span>
            <h1 class="section-title"><?php echo htmlspecialchars($copy['title']); ?></h1>
            <p><?php echo htmlspecialchars(str_replace('{amount}', (string) $amount, $copy['text'])); ?></p>
        </div>
        <div class="appointment-form-card appointment-form-card--next">
            <?php include __DIR__ . '/appointment-form/views/form.php'; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
