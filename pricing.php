<?php
$pageTitle = 'Pricing';
$pageDescription = 'Eat Rrite nutrition programs come in three package lengths — 30, 90 and 180 days.';
$currentPage = 'pricing';
$bannerTitle = 'Pricing';
$inner = require __DIR__ . '/constants/inner.php';
$copy = $inner['pricing'];
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($copy['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($copy['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($copy['lead']); ?></p>
        </div>
        <div class="pricing-grid">
            <?php foreach ($programPackages as $package): ?>
            <article class="card pricing-card<?php echo !empty($package['featured']) ? ' is-featured' : ''; ?>">
                <?php if (!empty($package['featured'])): ?>
                    <span class="pricing-badge">Most Popular</span>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                <p><?php echo htmlspecialchars($package['blurb']); ?></p>
                <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href('appointment.php')); ?>">Get Consultation</a>
            </article>
            <?php endforeach; ?>
        </div>
        <p class="pricing-note"><?php echo htmlspecialchars($copy['note']); ?></p>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
