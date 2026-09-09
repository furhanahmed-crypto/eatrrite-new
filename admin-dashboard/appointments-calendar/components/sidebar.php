<aside class="er-cal-sidebar">
    <?php require __DIR__ . '/mini-calendar.php'; ?>

    <section class="er-cal-panel">
        <h2>This day</h2>
        <?php if ($dayWindows === []): ?>
            <p class="er-cal-muted">Consultant is unavailable on <?php echo htmlspecialchars($selected->format('l'), ENT_QUOTES, 'UTF-8'); ?>s.</p>
        <?php else: ?>
            <ul class="er-cal-windows">
                <?php foreach ($dayWindows as $window): ?>
                    <li><?php echo htmlspecialchars($window['label'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p class="er-cal-muted"><?php echo count($dayEvents); ?> booking<?php echo count($dayEvents) === 1 ? '' : 's'; ?> on <?php echo htmlspecialchars($selected->format('j M'), ENT_QUOTES, 'UTF-8'); ?>.</p>
    </section>

    <section class="er-cal-panel">
        <h2>Categories</h2>
        <?php if ($counts === []): ?>
            <p class="er-cal-muted">No bookings in the sheet yet.</p>
        <?php else: ?>
            <ul class="er-cal-cats">
                <?php foreach ($counts as $row): ?>
                    <li>
                        <span class="er-cal-dot" style="background:<?php echo htmlspecialchars($row['color'], ENT_QUOTES, 'UTF-8'); ?>"></span>
                        <span class="er-cal-cats__label"><?php echo htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="er-cal-cats__count"><?php echo (int) $row['count']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</aside>
