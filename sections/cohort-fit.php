<?php $fit = $cohort['fit']; ?>
<section class="section section-mint" aria-labelledby="co-fit-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($fit['pill']); ?></span>
            <h2 class="section-title split-title" id="co-fit-title"><?php echo htmlspecialchars($fit['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($fit['lead']); ?></p>
        </div>
        <div class="co-fit-grid co-swipe" data-co-swipe>
            <?php foreach ($fit['items'] as $i => $item): ?>
                <article class="feature-card">
                    <div class="feature-card__icon"><i class="fa-solid <?php echo htmlspecialchars($item['icon']); ?>"></i></div>
                    <span class="feature-card__num"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
