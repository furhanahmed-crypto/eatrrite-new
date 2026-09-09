<?php
$pageTitle = 'Nutrition Programs | Eat Rrite — Weight Loss, Diabetes, Gut Health & More';
$pageDescription = 'Explore Eat Rrite\'s 30, 90 and 180-day nutrition programs for weight loss, diabetes management, gut health and women\'s hormonal health — built around your reports, not a template.';
$currentPage = 'programs';
$bannerTitle = 'Programs';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> Programs / Services</span>
            <h1 class="section-title split-title">Nutrition Programs Built Around You, Not a Diagnosis</h1>
            <p class="section-lead" style="margin-left:auto;margin-right:auto;text-align:center;">Every Eat Rrite program starts the same way — not with a fixed meal plan, but with a consultation call that looks at your actual reports, history and lifestyle. From there, we build a 30-day, 90-day or 180-day plan around your specific condition, using food you already eat.</p>
        </div>

        <div class="programs-grid programs-grid--four">
            <?php foreach ($programsLive as $program): ?>
            <article class="card program-card">
                <div class="img-wrap">
                    <img src="<?php echo htmlspecialchars($program['image']); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                </div>
                <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                <p><?php echo htmlspecialchars($program['summary']); ?></p>
                <a class="program-link" href="program.php?slug=<?php echo urlencode($program['slug']); ?>">View Program <i class="fa-solid fa-arrow-right"></i></a>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="coming-soon-box">
            <h3>Programs Coming Soon</h3>
            <p>Full detail is still being finalised for these confirmed program names:</p>
            <ul>
                <?php foreach ($programsComingSoon as $name): ?>
                    <li><?php echo htmlspecialchars($name); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <p class="programs-note">If you don't see your exact concern listed, <a href="appointment.php">book a consultation</a> and we'll tell you honestly whether we can help.</p>
    </div>
</section>
<?php include __DIR__ . '/sections/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
