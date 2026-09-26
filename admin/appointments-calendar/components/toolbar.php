<div class="er-cal-toolbar">
    <div class="er-cal-month-nav">
        <a href="?<?php echo htmlspecialchars(http_build_query([
            'month' => $prevMonth->format('Y-m'),
            'date' => $selected->format('Y-m-d'),
        ])); ?>" aria-label="Previous month">Previous</a>
        <p><?php echo htmlspecialchars($month->format('F Y')); ?></p>
        <a href="?<?php echo htmlspecialchars(http_build_query([
            'month' => $nextMonth->format('Y-m'),
            'date' => $selected->format('Y-m-d'),
        ])); ?>" aria-label="Next month">Next</a>
    </div>
</div>
