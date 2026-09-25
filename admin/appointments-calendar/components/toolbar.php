<div class="er-cal-toolbar">
    <h1>Consultations</h1>
    <div class="er-cal-month-nav">
        <a href="?<?php echo htmlspecialchars(http_build_query([
            'month' => $prevMonth->format('Y-m'),
            'date' => $selected->format('Y-m-d'),
        ])); ?>">Prev</a>
        <p><?php echo htmlspecialchars($month->format('F Y')); ?></p>
        <a href="?<?php echo htmlspecialchars(http_build_query([
            'month' => $nextMonth->format('Y-m'),
            'date' => $selected->format('Y-m-d'),
        ])); ?>">Next</a>
    </div>
</div>
