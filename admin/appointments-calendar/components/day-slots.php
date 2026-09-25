<section class="er-cal-day" aria-labelledby="er-cal-day-title">
    <h2 id="er-cal-day-title">Day · <?php echo htmlspecialchars($selected->format('Y-m-d')); ?></h2>
    <?php if ($dayRows === []): ?>
        <p class="er-cal-empty">No slots this day.</p>
    <?php endif; ?>
    <div class="er-cal-slots">
        <?php foreach ($dayRows as $row): ?>
            <div class="er-cal-slot">
                <div>
                    <p class="er-cal-slot__time"><?php echo htmlspecialchars((string) $row['display_time']); ?></p>
                    <?php if ($row['kind'] === 'booking'): ?>
                        <?php $event = $row['event']; ?>
                        <button
                            type="button"
                            class="er-cal-slot__booking"
                            data-er-event="<?php echo htmlspecialchars(json_encode([
                                'name' => $event['name'] ?? 'Client',
                                'service' => $event['service'] ?? '',
                                'date' => $event['date'] ?? $selected->format('Y-m-d'),
                                'time' => $event['time'] ?? $row['time'],
                                'phone' => $event['phone'] ?? '',
                                'email' => $event['email'] ?? '',
                                'meet_link' => $event['meet_link'] ?? '',
                                'display_meeting' => $row['display_meeting'] ?? $row['display_time'],
                                'questionnaire' => $event['questionnaire'] ?? [],
                            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
                        >
                            <?php echo htmlspecialchars((string) ($event['name'] ?: 'Booking')); ?>
                            · <?php echo htmlspecialchars((string) ($event['service'] ?? '')); ?>
                        </button>
                    <?php else: ?>
                        <p class="er-cal-slot__status"><?php echo $row['kind'] === 'disabled' ? 'Hidden' : 'Open'; ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($row['kind'] !== 'booking'): ?>
                    <button
                        type="button"
                        class="er-cal-slot__toggle"
                        data-er-hide-slot
                        data-time="<?php echo htmlspecialchars((string) $row['time']); ?>"
                        data-hidden="<?php echo $row['kind'] === 'disabled' ? '0' : '1'; ?>"
                    ><?php echo $row['kind'] === 'disabled' ? 'Show slot' : 'Hide slot'; ?></button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
