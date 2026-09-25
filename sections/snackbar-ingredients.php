<?php $ingredients = $snackbar['ingredients']; ?>
<section class="sb-block sb-block--wash">
    <div class="container">
        <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($ingredients['pill']); ?></span>
        <h2><?php echo htmlspecialchars($ingredients['title']); ?></h2>
        <p class="sb-lead"><?php echo htmlspecialchars($ingredients['lead']); ?></p>
        <ol class="sb-ingredients">
            <?php foreach ($ingredients['items'] as $index => $item): ?>
                <li>
                    <strong><?php echo ($index + 1) . '. ' . htmlspecialchars($item['name']); ?></strong>
                    <p><?php echo htmlspecialchars($item['text']); ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
        <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('snackbar-form/index.php')); ?>">Buy now</a>
    </div>
</section>
