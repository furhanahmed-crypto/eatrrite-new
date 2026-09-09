<?php
$pageTitle = 'Pricing | Eat Rrite Nutrition Coaching';
$pageDescription = 'Eat Rrite\'s nutrition programs come in three package lengths — 30, 90 and 180 days — so you can choose the level of support that fits your goal.';
$currentPage = 'pricing';
$bannerTitle = 'Pricing';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> Pricing</span>
            <h1 class="section-title split-title">Choose the Program Length That Fits Your Goal</h1>
            <p class="section-lead" style="margin-left:auto;margin-right:auto;text-align:center;">Every Eat Rrite program — Weight &amp; Lifestyle Management, Diabetes Management &amp; Reversal, Gut Health, and Female Hormone Health — is available in three lengths. Mukta will recommend the right one for you on your consultation call.</p>
        </div>

        <div class="pricing-grid">
            <?php foreach ($programPackages as $package): ?>
            <article class="card pricing-card<?php echo !empty($package['featured']) ? ' is-featured' : ''; ?>">
                <?php if (!empty($package['featured'])): ?>
                    <span class="pricing-badge">Most Popular</span>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                <p><?php echo htmlspecialchars($package['blurb']); ?></p>
                <a class="btn <?php echo !empty($package['featured']) ? 'btn-accent' : 'btn-primary'; ?>" href="appointment.php">Get Consultation</a>
            </article>
            <?php endforeach; ?>
        </div>

        <p class="pricing-note">Every package begins with a consultation call, where Mukta reviews your history, current symptoms and reports (or tells you which tests to get first) before recommending a length.</p>
        <p class="disclaimer" style="text-align:center;"><?php echo htmlspecialchars($site['results_disclaimer']); ?></p>
    </div>
</section>
<?php include __DIR__ . '/sections/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
