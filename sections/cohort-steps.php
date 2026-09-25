<?php $steps = $cohort['steps']; ?>
<section class="section section-primary-soft" id="how-it-works" aria-labelledby="co-steps-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($steps['pill']); ?></span>
            <h2 class="section-title split-title" id="co-steps-title"><?php echo htmlspecialchars($steps['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($steps['lead']); ?></p>
        </div>
        <div class="process-grid">
            <?php foreach ($steps['items'] as $item): ?>
                <article class="process-card">
                    <div class="process-num"><?php echo htmlspecialchars($item['num']); ?></div>
                    <div class="process-icon"><i class="fa-solid <?php echo htmlspecialchars($item['icon']); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($item['text']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="pricing-note"><?php echo htmlspecialchars($steps['note']); ?></p>
    </div>
</section>
