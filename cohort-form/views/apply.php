<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$config = cohort_config();
$dbError = '';
try {
    $remaining = cohort_service()->remainingSpots();
} catch (Throwable $e) {
    $remaining = (int) $config['spots'];
    $dbError = 'MySQL is not reachable from this machine. The form is visible, but payment will fail until db.local.php can connect.';
}
$csrf = cohort_csrf_token();
$fee = (int) $config['consultation_rupees'];
$monthly = number_format((int) $config['monthly_rupees']);
$pageTitle = 'Apply for the Cohort';
$pageDescription = 'Pay the consultation fee and reserve a complimentary first month in the Eat Rrite cohort.';
$currentPage = 'cohort';
$bannerTitle = 'Apply for the Cohort';
$bannerCrumb = 'Cohort';
$extraCss = ['cohort-form/assets/cohort.css'];
include dirname(__DIR__, 2) . '/includes/header.php';
include dirname(__DIR__, 2) . '/sections/page-banner.php';
?>
<?php if ($remaining <= 0): ?>
    <section class="section section-cream">
        <div class="container">
            <?php $closedTitle = "This month's cohort is full"; require __DIR__ . '/closed.php'; ?>
            <p><a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href('cohort.php')); ?>">Back to cohort</a></p>
        </div>
    </section>
<?php else: ?>
<section class="section section-cream">
    <div class="container appointment-layout appointment-layout--next">
        <div class="appointment-copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo (int) $remaining; ?> spots left</span>
            <h1 class="section-title">Pay the consultation fee. Claim your first month free.</h1>
            <p>A one-time ₹<?php echo number_format($fee); ?> consultation reserves your place — and unlocks a complimentary first month in the ₹<?php echo $monthly; ?> cohort. From month two, continue or step away. No lock-in.</p>
            <?php if ($dbError !== ''): ?><p class="er-alert"><?php echo htmlspecialchars($dbError); ?></p><?php endif; ?>
        </div>
        <div class="appointment-form-card appointment-form-card--next">
            <form
                class="er-form"
                data-er-cohort-form
                data-api="api"
                data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
                data-fee-rupees="<?php echo $fee; ?>"
                novalidate
            >
                <div class="er-alert" data-er-alert hidden></div>
                <div class="er-grid">
                    <label class="er-field"><span>Full name</span><input name="name" autocomplete="name" maxlength="80" required placeholder="Your name"></label>
                    <label class="er-field"><span>Email</span><input type="email" name="email" autocomplete="email" maxlength="120" required placeholder="you@example.com"></label>
                </div>
                <label class="er-field"><span>Mobile number</span><input type="tel" name="mobilenumber" inputmode="numeric" maxlength="13" required placeholder="10-digit number"></label>
                <p class="er-total"><span>Consultation fee</span><span>₹<?php echo number_format($fee); ?></span></p>
                <button type="submit" class="er-submit" data-er-submit>Pay ₹<?php echo number_format($fee); ?> consultation fee</button>
            </form>
        </div>
    </div>
</section>
<?php endif; ?>
<?php if ($remaining > 0): ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="assets/cohort.js" defer></script>
<?php endif; ?>
<?php include dirname(__DIR__, 2) . '/includes/footer.php'; ?>
