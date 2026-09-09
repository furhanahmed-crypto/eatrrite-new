<?php
$miniTitle = $month->format('F Y');
$weekdayLabels = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
?>
<section class="er-cal-panel er-cal-mini">
    <div class="er-cal-mini__nav">
        <a class="er-cal-icon-btn" href="<?php echo htmlspecialchars($query($view, $prevMonth), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Previous month">‹</a>
        <h2><?php echo htmlspecialchars($miniTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
        <a class="er-cal-icon-btn" href="<?php echo htmlspecialchars($query($view, $nextMonth), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Next month">›</a>
    </div>
    <div class="er-cal-mini__weekdays">
        <?php foreach ($weekdayLabels as $label): ?>
            <span><?php echo $label; ?></span>
        <?php endforeach; ?>
    </div>
    <div class="er-cal-mini__days">
        <?php foreach ($monthCells as $cell): ?>
            <a
                class="er-cal-mini__day<?php
                    echo $cell['in_month'] ? '' : ' is-outside';
                    echo $cell['is_today'] ? ' is-today' : '';
                    echo $cell['is_selected'] ? ' is-selected' : '';
                    echo $cell['count'] > 0 ? ' has-events' : '';
                ?>"
                href="<?php echo htmlspecialchars($query($view, $slots->parseDate($cell['iso'])), ENT_QUOTES, 'UTF-8'); ?>"
            ><?php echo (int) $cell['day']; ?></a>
        <?php endforeach; ?>
    </div>
</section>
