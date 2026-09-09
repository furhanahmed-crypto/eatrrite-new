<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$_SESSION['er_admin_dashboard'] = false;
unset($_SESSION['er_admin_dashboard']);
header('Location: /admin-dashboard/appointments-calendar/', true, 303);
exit;
