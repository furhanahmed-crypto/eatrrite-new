<?php $why = $home['why']; ?>
<section class="section section-mint" id="why-choose">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($why['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($why['title']); ?></h2>
        </div>
        <div class="why-grid why-grid--five">
            <?php foreach ($why['items'] as $item): ?>
            <article class="feature-card">
                <div class="feature-card__icon"><i class="fa-solid <?php echo htmlspecialchars($item['icon']); ?>"></i></div>
                <span class="feature-card__num"><?php echo htmlspecialchars($item['num']); ?></span>
                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                <p><?php echo htmlspecialchars($item['text']); ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
