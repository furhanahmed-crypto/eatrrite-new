<?php

declare(strict_types=1);

function admin_dashboard_password(): string
{
    return (string) (appointment_config()['admin_dashboard_password'] ?? '');
}

function admin_dashboard_authed(): bool
{
    if (admin_dashboard_password() === '') {
        return true;
    }

    return !empty($_SESSION['er_admin_dashboard']);
}

function admin_dashboard_require(): void
{
    if (admin_dashboard_authed()) {
        return;
    }

    $error = '';
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $csrf = (string) ($_POST['csrf'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        if (!hash_equals(appointment_csrf_token(), $csrf)) {
            $error = 'Your session expired. Try again.';
        } elseif (!hash_equals(admin_dashboard_password(), $password)) {
            $error = 'That password is not correct.';
        } else {
            $_SESSION['er_admin_dashboard'] = true;
            header('Location: ' . admin_dashboard_current_url(), true, 303);
            exit;
        }
    }

    require __DIR__ . '/login.php';
    exit;
}

function admin_dashboard_current_url(): string
{
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/admin-dashboard/appointments-calendar/');

    return $uri !== '' ? $uri : '/admin-dashboard/appointments-calendar/';
}

function admin_dashboard_logout_url(): string
{
    return '/admin-dashboard/logout.php';
}
