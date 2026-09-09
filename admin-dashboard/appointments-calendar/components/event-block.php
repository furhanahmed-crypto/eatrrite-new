<button
    type="button"
    class="er-cal-block"
    style="
        --er-event: <?php echo htmlspecialchars((string) $event['color'], ENT_QUOTES, 'UTF-8'); ?>;
        top: <?php echo htmlspecialchars(number_format((float) $event['top'], 3, '.', ''), ENT_QUOTES, 'UTF-8'); ?>%;
        height: <?php echo htmlspecialchars(number_format((float) $event['height'], 3, '.', ''), ENT_QUOTES, 'UTF-8'); ?>%;
        left: <?php echo htmlspecialchars(number_format((float) $event['left'], 3, '.', ''), ENT_QUOTES, 'UTF-8'); ?>%;
        width: <?php echo htmlspecialchars(number_format((float) $event['width'], 3, '.', ''), ENT_QUOTES, 'UTF-8'); ?>%;
    "
    data-er-event="<?php echo htmlspecialchars(json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>"
>
    <strong><?php echo htmlspecialchars((string) $event['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
    <span><?php echo htmlspecialchars((string) $event['service'], ENT_QUOTES, 'UTF-8'); ?></span>
    <em><?php echo htmlspecialchars($event['display_time'] . ' – ' . $event['display_meeting_end'], ENT_QUOTES, 'UTF-8'); ?></em>
</button>
