<script src="<?php echo htmlspecialchars($assetBase ?? '/admin-dashboard/appointments-calendar/assets', ENT_QUOTES, 'UTF-8'); ?>/calendar.js?v=<?php echo (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar.js') ?: time()); ?>"></script>
</body>
</html>
