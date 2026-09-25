<?php

declare(strict_types=1);

$pageTitle = $title ?? 'Consultations';
$assetBase = $assetBase ?? '/admin/appointments-calendar/assets';
$assetVersion = (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar.css') ?: time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> · Eat Rrite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Bricolage+Grotesque:opsz,wght@12..96,400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/calendar.css?v=<?php echo $assetVersion; ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>/calendar-ui.css?v=<?php echo (int) (@filemtime(__DIR__ . '/appointments-calendar/assets/calendar-ui.css') ?: time()); ?>">
    <link rel="stylesheet" href="/admin/assets/admin-lists.css?v=<?php echo (int) (@filemtime(__DIR__ . '/assets/admin-lists.css') ?: time()); ?>">
</head>
<body class="er-admin">
