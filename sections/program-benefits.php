<section class="section section-mint program-detail">
    <div class="container program-split program-split--reverse program-split--flush">
        <div class="program-split__media">
            <img src="<?php echo htmlspecialchars(er_href($program['image_secondary'])); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
        </div>
        <div class="program-split__copy">
            <h3><?php echo htmlspecialchars($copy['benefitsTitle']); ?></h3>
            <ul class="check-list">
                <?php foreach ($program['benefits'] as $benefit): ?>
                    <li>✓ <?php echo htmlspecialchars($benefit); ?></li>
                <?php endforeach; ?>
            </ul>
            <h3><?php echo htmlspecialchars($copy['idealTitle']); ?></h3>
            <p><?php echo htmlspecialchars($program['ideal']); ?></p>
        </div>
    </div>
</section>
