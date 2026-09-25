<?php $benefits = $snackbar['benefits']; ?>
<section class="sb-block sb-block--cream">
    <div class="container">
        <div class="sb-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($benefits['pill']); ?></span>
            <h2><?php echo htmlspecialchars($benefits['title']); ?></h2>
        </div>
        <div class="sb-grid-3">
            <?php foreach ($benefits['items'] as $item): ?>
                <article class="sb-card">
                    <span class="sb-num"><?php echo htmlspecialchars($item['num']); ?></span>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($item['text']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="sb-center"><a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('snackbar-form/index.php')); ?>">Buy now</a></p>
    </div>
</section>
