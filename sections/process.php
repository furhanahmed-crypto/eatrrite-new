<?php $process = $home['process']; ?>
<section class="section section-primary-soft">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($process['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($process['title']); ?></h2>
        </div>
        <div class="process-grid">
            <?php foreach ($process['steps'] as $step): ?>
            <article class="process-card">
                <div class="process-num"><?php echo htmlspecialchars($step['num']); ?></div>
                <div class="process-icon"><i class="fa-solid <?php echo htmlspecialchars($step['icon']); ?>"></i></div>
                <h3><?php echo htmlspecialchars($step['title']); ?></h3>
                <p><?php echo htmlspecialchars($step['text']); ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
