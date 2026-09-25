<?php require __DIR__ . '/confirm.php'; ?>
<script src="/admin/assets/admin-confirm.js?v=<?php echo (int) (@filemtime(__DIR__ . '/assets/admin-confirm.js') ?: time()); ?>"></script>
<script src="<?php echo htmlspecialchars($assetBase ?? '/admin/appointments-calendar/assets', ENT_QUOTES, 'UTF-8'); ?>/calendar.js?v=<?php echo (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar.js') ?: time()); ?>"></script>
<script src="<?php echo htmlspecialchars($assetBase ?? '/admin/appointments-calendar/assets', ENT_QUOTES, 'UTF-8'); ?>/calendar-cancel.js?v=<?php echo (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar-cancel.js') ?: time()); ?>"></script>
</body>
</html>
