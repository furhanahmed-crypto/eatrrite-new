<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$payload = cohort_verified_application();
$application = is_array($payload['verified'] ?? null) ? $payload['verified'] : null;
$monthly = number_format((int) cohort_config()['monthly_rupees']);
$pageTitle = 'Thank you';
$currentPage = 'cohort';
$bannerTitle = 'Thank you';
$bannerCrumb = 'Cohort';
$extraCss = ['snackbar-form/assets/snackbar-form.css'];
include dirname(__DIR__, 2) . '/includes/header.php';
include dirname(__DIR__, 2) . '/sections/page-banner.php';
?>
<section class="section section-cream">
    <div class="container sb-thanks">
        <div class="sb-thanks__card">
            <div class="sb-thanks__mark" aria-hidden="true">✓</div>
            <?php if ($application): ?>
                <h1>Thank you, <?php echo htmlspecialchars((string) $application['name']); ?></h1>
                <p>Your ₹<?php echo number_format((int) ($application['amount_rupees'] ?? 0)); ?> consultation is confirmed. The first month is complimentary. From next month you may continue at ₹<?php echo $monthly; ?> or step away. We will write to you within 48 hours.</p>
            <?php else: ?>
                <h1>Thank you</h1>
                <p>No recent cohort application was found on this device.</p>
            <?php endif; ?>
            <p class="sb-thanks__note">Questions? Write to <a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a>.</p>
            <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href('index.php')); ?>">Back home</a>
        </div>
    </div>
</section>
<?php include dirname(__DIR__, 2) . '/includes/footer.php'; ?>
