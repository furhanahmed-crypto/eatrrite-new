<?php $block = $home['programs']; ?>
<section class="section section-mint programs-section">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($block['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($block['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($block['lead']); ?></p>
        </div>
        <div class="programs-grid programs-grid--four">
            <?php foreach ($programsLive as $program): ?>
            <article class="card program-card">
                <div class="img-wrap">
                    <img src="<?php echo htmlspecialchars(er_href($program['image'])); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
                </div>
                <h3><?php echo htmlspecialchars($program['short']); ?></h3>
                <p><?php echo htmlspecialchars($program['summary']); ?></p>
                <a class="program-link" href="<?php echo htmlspecialchars(er_href('program.php?slug=' . urlencode($program['slug']))); ?>">Learn More <i class="fa-solid fa-arrow-right"></i></a>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="section-cta-row">
            <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href($block['cta']['href'])); ?>"><?php echo htmlspecialchars($block['cta']['label']); ?></a>
        </div>
    </div>
</section>
