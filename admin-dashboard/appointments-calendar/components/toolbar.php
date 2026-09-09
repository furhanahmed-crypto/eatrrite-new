<div class="er-cal-toolbar">
    <div>
        <p class="er-admin-kicker">My schedule</p>
        <h2><?php echo $view === 'day'
            ? htmlspecialchars($selected->format('l, j F Y'), ENT_QUOTES, 'UTF-8')
            : htmlspecialchars($month->format('F Y'), ENT_QUOTES, 'UTF-8'); ?></h2>
    </div>
    <div class="er-cal-toolbar__actions">
        <div class="er-cal-nav">
            <a class="er-cal-icon-btn" href="<?php echo htmlspecialchars($query($view, $view === 'day' ? $prevDay : $prevMonth), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Previous">‹</a>
            <a class="er-cal-chip" href="<?php echo htmlspecialchars($query($view, $today), ENT_QUOTES, 'UTF-8'); ?>">Today</a>
            <a class="er-cal-icon-btn" href="<?php echo htmlspecialchars($query($view, $view === 'day' ? $nextDay : $nextMonth), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Next">›</a>
        </div>
        <div class="er-cal-toggle" role="tablist" aria-label="Calendar view">
            <a class="er-cal-toggle__btn<?php echo $view === 'day' ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($query('day', $selected), ENT_QUOTES, 'UTF-8'); ?>">Day</a>
            <a class="er-cal-toggle__btn<?php echo $view === 'month' ? ' is-active' : ''; ?>" href="<?php echo htmlspecialchars($query('month', $selected), ENT_QUOTES, 'UTF-8'); ?>">Month</a>
        </div>
    </div>
</div>
