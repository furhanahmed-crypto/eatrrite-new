<?php
$svc = $home['services'];
$icons = $svc['icons'];
?>
<section class="section services-section" id="services">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($svc['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($svc['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($svc['lead']); ?></p>
        </div>
        <div class="services-nourio-grid">
            <?php foreach ($programsLive as $i => $program): ?>
            <article class="svc-card svc-card--photo">
                <div class="svc-card__media">
                    <img class="svc-card__photo" src="<?php echo htmlspecialchars(er_href($program['image'])); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                    <div class="svc-card__shade"></div>
                    <div class="svc-card__copy">
                        <div class="svc-icon"><i class="fa-solid <?php echo $icons[$i] ?? 'fa-heart'; ?>"></i></div>
                        <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                        <p><?php echo htmlspecialchars($program['summary']); ?></p>
                        <a class="svc-arrow" href="<?php echo htmlspecialchars(er_href('program.php?slug=' . urlencode($program['slug']))); ?>" aria-label="Learn more about <?php echo htmlspecialchars($program['short']); ?>"><i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="section-cta-row">
            <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href($svc['cta']['href'])); ?>"><?php echo htmlspecialchars($svc['cta']['label']); ?></a>
        </div>
    </div>
</section>
