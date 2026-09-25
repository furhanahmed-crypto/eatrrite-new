<?php

declare(strict_types=1);

$title = 'Consultant sign in';
$assetBase = '/admin/appointments-calendar/assets';
$error = $error ?? '';
require __DIR__ . '/layout-start.php';
?>
<main class="er-admin-login">
    <form method="post" class="er-admin-login__card">
        <p class="er-admin-kicker">Eat Rrite</p>
        <h1>Consultant calendar</h1>
        <p class="er-admin-login__copy">Sign in to review booked consultations.</p>
        <?php if ($error !== ''): ?>
            <p class="er-admin-alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(appointment_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <label>
            <span>Password</span>
            <input type="password" name="password" required autofocus autocomplete="current-password">
        </label>
        <button type="submit">Open calendar</button>
    </form>
</main>
<?php require __DIR__ . '/layout-end.php'; ?>
