<?php $product = $snackbar['product']; ?>
<section class="sb-block sb-block--cream" id="inside">
    <div class="container sb-split">
        <div>
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($product['pill']); ?></span>
            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p class="sb-lead"><?php echo htmlspecialchars($product['lead']); ?></p>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('snackbar-form/index.php')); ?>">Buy now</a>
        </div>
        <div class="sb-stack">
            <?php foreach ($product['items'] as $item): ?>
                <article class="sb-card">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($item['text']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
