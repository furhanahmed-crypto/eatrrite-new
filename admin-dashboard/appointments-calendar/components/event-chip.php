<button
    type="button"
    class="er-cal-chip-event"
    style="--er-event: <?php echo htmlspecialchars((string) $event['color'], ENT_QUOTES, 'UTF-8'); ?>"
    data-er-event="<?php echo htmlspecialchars(json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
>
    <strong><?php echo htmlspecialchars((string) $event['display_time'], ENT_QUOTES, 'UTF-8'); ?></strong>
    <span><?php echo htmlspecialchars((string) $event['name'], ENT_QUOTES, 'UTF-8'); ?></span>
</button>
