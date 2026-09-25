<?php

declare(strict_types=1);

$pageTitle = $title ?? 'Appointments calendar';
$assetBase = $assetBase ?? '/admin-dashboard/appointments-calendar/assets';
$assetVersion = (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar.css') ?: time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> · Eat Rrite</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/calendar.css?v=<?php echo $assetVersion; ?>">
</head>
<body class="er-admin">
