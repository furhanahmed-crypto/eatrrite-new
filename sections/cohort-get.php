<?php $get = $cohort['get']; ?>
<section class="section" aria-labelledby="co-get-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($get['pill']); ?></span>
            <h2 class="section-title split-title" id="co-get-title"><?php echo htmlspecialchars($get['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($get['lead']); ?></p>
        </div>
        <div class="why-grid why-grid--five">
            <?php foreach ($get['items'] as $item): ?>
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
