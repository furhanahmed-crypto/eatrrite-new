<?php require dirname(__DIR__, 2) . '/header.php'; ?>
<main
    class="er-admin-main"
    data-er-calendar
    data-date="<?php echo htmlspecialchars($selected->format('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>"
    data-csrf="<?php echo htmlspecialchars(appointment_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>"
    data-hide-slot-url="/admin/appointments-calendar/api/toggle-slot.php"
    data-cancel-url="/admin/appointments-calendar/api/cancel-booking.php"
>
    <div class="er-admin-wrap">
        <?php require __DIR__ . '/toolbar.php'; ?>
        <?php if ($error !== ''): ?>
            <p class="er-admin-alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <?php require __DIR__ . '/month-grid.php'; ?>
        <?php require __DIR__ . '/day-slots.php'; ?>
    </div>
</main>
<?php require __DIR__ . '/detail-drawer.php'; ?>
