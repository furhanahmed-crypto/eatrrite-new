<?php $items = $home['marquee']; ?>
<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <?php for ($loop = 0; $loop < 2; $loop++): ?>
            <div class="marquee-group"<?php echo $loop === 1 ? ' aria-hidden="true"' : ''; ?>>
                <?php foreach ($items as $item): ?>
                    <span><?php echo htmlspecialchars($item); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>
