<?php $cta = $cohort['cta']; ?>
<section class="section cta-section" id="apply" aria-labelledby="co-cta-title">
    <div class="container">
        <div class="cta-box">
            <div class="cta-box__glow"></div>
            <div>
                <span class="pill-label pill-label--on-dark"><span class="pill-dot"></span> <?php echo htmlspecialchars($cta['pill']); ?></span>
                <h2 class="split-title" id="co-cta-title"><?php echo htmlspecialchars($cta['title']); ?></h2>
                <p><?php echo htmlspecialchars($cta['text']); ?></p>
            </div>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars($cta['cta']['href']); ?>" data-er-open-booking><?php echo htmlspecialchars($cta['cta']['label']); ?></a>
        </div>
    </div>
</section>
