<?php
$get = $cohort['get'];
$steps = $cohort['steps'];
$cta = $cohort['cta'];
?>
<section class="section" id="how-it-works" aria-labelledby="co-get-title">
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

        <div class="co-consult-sub">
            <h3><?php echo htmlspecialchars($steps['title']); ?></h3>
            <p><?php echo htmlspecialchars($steps['lead']); ?></p>
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
        <div class="co-consult-cta">
            <a class="btn btn-accent" href="<?php echo htmlspecialchars($cta['cta']['href']); ?>" data-er-open-booking><?php echo htmlspecialchars($cta['cta']['label']); ?></a>
        </div>
    </div>
</section>