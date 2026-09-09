<div class="er-cal-month">
    <div class="er-cal-month__weekdays">
        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
    </div>
    <div class="er-cal-month__grid">
        <?php foreach ($monthCells as $cell): ?>
            <div
                class="er-cal-month__cell<?php
                    echo $cell['in_month'] ? '' : ' is-outside';
                    echo $cell['is_today'] ? ' is-today' : '';
                    echo $cell['is_selected'] ? ' is-selected' : '';
                ?>"
            >
                <a
                    class="er-cal-month__hit"
                    href="<?php echo htmlspecialchars($query('day', $slots->parseDate($cell['iso'])), ENT_QUOTES, 'UTF-8'); ?>"
                    aria-label="<?php echo htmlspecialchars($cell['iso'], ENT_QUOTES, 'UTF-8'); ?>"
                ></a>
                <span class="er-cal-month__num"><?php echo (int) $cell['day']; ?></span>
                <?php if ($cell['count'] > 0): ?>
                    <span class="er-cal-month__badge"><?php echo (int) $cell['count']; ?></span>
                <?php endif; ?>
                <div class="er-cal-month__events">
                    <?php foreach (array_slice($cell['events'], 0, 3) as $event): ?>
                        <?php require __DIR__ . '/event-chip.php'; ?>
                    <?php endforeach; ?>
                    <?php if ($cell['count'] > 3): ?>
                        <span class="er-cal-month__more">+<?php echo $cell['count'] - 3; ?> more</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
