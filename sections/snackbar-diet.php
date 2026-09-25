<?php $diet = $snackbar['diet']; ?>
<section class="sb-block sb-block--wash">
    <div class="container sb-split">
        <div>
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($diet['pill']); ?></span>
            <h2><?php echo htmlspecialchars($diet['title']); ?></h2>
            <p class="sb-lead"><?php echo htmlspecialchars($diet['text']); ?></p>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('snackbar-form/index.php')); ?>">Buy now</a>
        </div>
        <ul class="sb-points">
            <?php foreach ($diet['points'] as $point): ?>
                <li><?php echo htmlspecialchars($point); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
