<section class="section services-section" id="services">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> Programs</span>
            <h2 class="section-title split-title">Built around your reports, not a template</h2>
            <p class="section-lead" style="margin-left:auto;margin-right:auto;text-align:center;">Whether it's weight loss, diabetes reversal, gut health or hormonal balance — every Eat Rrite program starts with a consultation. Choose a 30-day reset, a 90-day journey, or a 180-day commitment.</p>
        </div>

        <div class="services-nourio-grid">
            <?php foreach ($programsLive as $i => $program): ?>
            <article class="svc-card">
                <img class="svc-card__bg" src="<?php echo htmlspecialchars($program['image']); ?>" alt="">
                <div class="svc-card__overlay"></div>
                <div class="svc-card__content">
                    <div class="svc-card__top">
                        <div class="svc-icon">
                            <i class="fa-solid <?php echo ['fa-plate-wheat', 'fa-droplet', 'fa-leaf', 'fa-venus'][$i] ?? 'fa-heart'; ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                        <p><?php echo htmlspecialchars($program['summary']); ?></p>
                        <a class="svc-arrow" href="program.php?slug=<?php echo urlencode($program['slug']); ?>" aria-label="Learn more about <?php echo htmlspecialchars($program['short']); ?>"><i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="svc-card__img">
                        <img src="<?php echo htmlspecialchars($program['image']); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="section-cta-row">
            <a class="btn btn-primary" href="programs.php">Explore Our Programs</a>
        </div>
    </div>
</section>
