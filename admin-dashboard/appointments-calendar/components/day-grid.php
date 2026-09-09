<div class="er-cal-day">
    <?php if ($dayLayout['unavailable']): ?>
        <p class="er-cal-empty">No consultation hours on this day, and no bookings on the sheet.</p>
    <?php elseif ($dayLayout['rows'] === []): ?>
        <p class="er-cal-empty">No 15-minute slots on this day.</p>
    <?php endif; ?>

    <?php if ($dayLayout['rows'] !== []): ?>
        <div class="er-cal-slots">
            <?php foreach ($dayLayout['rows'] as $row): ?>
                <?php if ($row['kind'] === 'gap'): ?>
                    <div class="er-cal-slot-gap">Break</div>
                    <?php continue; ?>
                <?php endif; ?>

                <?php if ($row['kind'] === 'booking'): ?>
                    <?php $event = $row['event']; ?>
                    <button
                        type="button"
                        class="er-cal-slot is-booked"
                        style="--er-event: <?php echo htmlspecialchars((string) $event['color'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-er-event="<?php echo htmlspecialchars(json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
                    >
                        <time><?php echo htmlspecialchars((string) $row['display_time'], ENT_QUOTES, 'UTF-8'); ?></time>
                        <span>
                            <strong><?php echo htmlspecialchars((string) $event['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <em><?php echo htmlspecialchars((string) $event['service'], ENT_QUOTES, 'UTF-8'); ?></em>
                            <small><?php echo htmlspecialchars($event['display_time'] . ' – ' . $event['display_meeting_end'] . ' consult', ENT_QUOTES, 'UTF-8'); ?></small>
                        </span>
                    </button>
                    <?php continue; ?>
                <?php endif; ?>

                <?php if ($row['kind'] === 'busy'): ?>
                    <?php $event = $row['event']; ?>
                    <button
                        type="button"
                        class="er-cal-slot is-busy"
                        data-er-event="<?php echo htmlspecialchars(json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
                    >
                        <time><?php echo htmlspecialchars((string) $row['display_time'], ENT_QUOTES, 'UTF-8'); ?></time>
                        <span>Busy · continues from <?php echo htmlspecialchars((string) $event['display_time'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </button>
                    <?php continue; ?>
                <?php endif; ?>

                <div class="er-cal-slot <?php echo $row['kind'] === 'disabled' ? 'is-hidden' : 'is-open'; ?>">
                    <time><?php echo htmlspecialchars((string) $row['display_time'], ENT_QUOTES, 'UTF-8'); ?></time>
                    <span data-er-slot-status><?php echo $row['kind'] === 'disabled' ? 'Hidden' : 'Open'; ?></span>
                    <label class="er-cal-hide">
                        <span>Hide slot</span>
                        <input
                            type="checkbox"
                            role="switch"
                            data-er-hide-slot
                            data-time="<?php echo htmlspecialchars((string) $row['time'], ENT_QUOTES, 'UTF-8'); ?>"
                            aria-label="Hide this slot from customer booking"
                            <?php echo !empty($row['hidden']) || $row['kind'] === 'disabled' ? 'checked' : ''; ?>
                        >
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
