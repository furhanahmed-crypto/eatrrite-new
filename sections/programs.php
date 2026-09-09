<section class="section section-mint programs-section">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> Our Programs</span>
            <h2 class="section-title split-title">Nutrition programs built around you</h2>
            <p class="section-lead" style="margin-left:auto;margin-right:auto;text-align:center;">Every program starts with a consultation call that looks at your reports, history and lifestyle — then we build a 30, 90 or 180-day plan around food you already eat.</p>
        </div>
        <div class="programs-grid programs-grid--four">
            <?php foreach ($programsLive as $program): ?>
            <article class="card program-card">
                <div class="img-wrap">
                    <img src="<?php echo htmlspecialchars($program['image']); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                </div>
                <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                <p><?php echo htmlspecialchars($program['summary']); ?></p>
                <a class="program-link" href="program.php?slug=<?php echo urlencode($program['slug']); ?>">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="section-cta-row">
            <a class="btn btn-primary" href="pricing.php">View Pricing</a>
            <a class="btn btn-outline" href="appointment.php">Book Your Consultation</a>
        </div>
    </div>
</section>
