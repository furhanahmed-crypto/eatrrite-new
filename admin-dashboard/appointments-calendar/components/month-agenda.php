<?php
$monthIso = $month->format('Y-m');
$monthEvents = array_values(array_filter(
    $appointments,
    static fn (array $row): bool => str_starts_with((string) $row['date'], $monthIso)
));
?>
<div class="er-cal-agenda" <?php echo $view === 'month' ? '' : 'hidden'; ?>>
    <h3>Bookings this month</h3>
    <?php if ($monthEvents === []): ?>
        <p class="er-cal-muted">No sheet bookings in <?php echo htmlspecialchars($month->format('F Y'), ENT_QUOTES, 'UTF-8'); ?>.</p>
    <?php else: ?>
        <ol>
            <?php foreach ($monthEvents as $event): ?>
                <li>
                    <button
                        type="button"
                        class="er-cal-agenda__item"
                        data-er-event="<?php echo htmlspecialchars(json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
                    >
                        <span class="er-cal-dot" style="background:<?php echo htmlspecialchars((string) $event['color'], ENT_QUOTES, 'UTF-8'); ?>"></span>
                        <span>
                            <strong><?php echo htmlspecialchars((string) $event['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <em><?php echo htmlspecialchars($event['display_date'] . ' · ' . $event['display_time'], ENT_QUOTES, 'UTF-8'); ?></em>
                            <small><?php echo htmlspecialchars((string) $event['service'], ENT_QUOTES, 'UTF-8'); ?></small>
                        </span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</div>
