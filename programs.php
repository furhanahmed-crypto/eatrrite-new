<?php
$pageTitle = 'Nutrition Programs';
$pageDescription = 'Explore Eat Rrite\'s 30, 90 and 180-day nutrition programs for weight loss, diabetes, gut health and women\'s hormonal health.';
$currentPage = 'programs';
$bannerTitle = 'Programs';
$inner = require __DIR__ . '/constants/inner.php';
$copy = $inner['programs'];
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
        <div class="programs-grid programs-grid--two">
            <?php foreach ($programsLive as $program): ?>
            <article class="card program-card">
                <div class="img-wrap img-wrap--wide">
                    <img src="<?php echo htmlspecialchars(er_href($program['image'])); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                </div>
                <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                <p><?php echo htmlspecialchars($program['summary']); ?></p>
                <a class="program-link" href="<?php echo htmlspecialchars(er_href('program.php?slug=' . urlencode($program['slug']))); ?>">View Program <i class="fa-solid fa-arrow-right"></i></a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
