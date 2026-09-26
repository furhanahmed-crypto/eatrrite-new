<?php $investment = $cohort['investment']; ?>
<section class="section cta-section" aria-labelledby="co-invest-title">
    <div class="container">
        <div class="cta-box">
            <div class="cta-box__glow"></div>
            <div>
                <span class="pill-label pill-label--on-dark"><span class="pill-dot"></span> <?php echo htmlspecialchars($investment['pill']); ?></span>
                <h2 class="split-title" id="co-invest-title"><?php echo htmlspecialchars($investment['title']); ?></h2>
                <p><?php echo htmlspecialchars($investment['text']); ?></p>
            </div>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars($investment['cta']['href']); ?>" data-er-open-booking><?php echo htmlspecialchars($investment['cta']['label']); ?></a>
        </div>
    </div>
</section>
