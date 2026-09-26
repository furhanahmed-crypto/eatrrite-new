<?php
$labels = [
    ['S', 'Sun'], ['M', 'Mon'], ['T', 'Tue'], ['W', 'Wed'],
    ['T', 'Thu'], ['F', 'Fri'], ['S', 'Sat'],
];
?>
<div class="er-cal-month">
    <div class="er-cal-month__labels">
        <?php foreach ($labels as $label): ?>
            <div><span class="is-short"><?php echo $label[0]; ?></span><span class="is-full"><?php echo $label[1]; ?></span></div>
        <?php endforeach; ?>
    </div>
    <div class="er-cal-month__grid">
        <?php foreach ($monthCells as $cell): ?>
            <?php
            $href = '?' . http_build_query([
                'month' => $month->format('Y-m'),
                'date' => $cell['iso'],
            ]);
            $class = 'er-cal-cell';
            if (!$cell['in_month']) {
                $class .= ' is-muted';
            }
            if ($cell['is_selected']) {
                $class .= ' is-selected';
            }
            if ($cell['is_today']) {
                $class .= ' is-today';
            }
            ?>
            <a
                class="<?php echo $class; ?>"
                href="<?php echo htmlspecialchars($href); ?>"
                <?php if ($cell['count'] > 0): ?>title="<?php echo (int) $cell['count']; ?> booked"<?php endif; ?>
            >
                <span><?php echo (int) $cell['day']; ?></span>
                <?php if ($cell['count'] > 0): ?>
                    <small><?php echo (int) $cell['count']; ?></small>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
