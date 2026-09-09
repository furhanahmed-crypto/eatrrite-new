<?php
require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? 'Eat Rrite - Nutritionist, Holistic Health and Wellness';
$currentPage = $currentPage ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Eat Rrite is a nutrition and holistic wellness platform offering personalised diet programs, yoga, and lifestyle guidance.'); ?>">
    <link rel="icon" href="assets/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($site['hours']); ?></span>
            <span class="topbar-links">
                <a href="<?php echo htmlspecialchars($site['email_href']); ?>"><i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($site['email']); ?></a>
                <a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($site['phone']); ?></a>
            </span>
        </div>
    </div>

    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="index.php">
                <img src="assets/images/logo/logo-horizontal.png" alt="Eat Rrite">
            </a>
            <button class="nav-toggle" type="button" aria-label="Open menu">☰</button>
            <nav class="site-nav">
                <a href="index.php" class="<?php echo $currentPage === 'home' ? 'is-active' : ''; ?>">Home</a>
                <a href="about.php" class="<?php echo $currentPage === 'about' ? 'is-active' : ''; ?>">About</a>
                <a href="programs.php" class="<?php echo $currentPage === 'programs' ? 'is-active' : ''; ?>">Programs</a>
                <a href="pricing.php" class="<?php echo $currentPage === 'pricing' ? 'is-active' : ''; ?>">Pricing</a>
                <a href="index.php#blog">Blog</a>
                <a class="nav-contact" href="contact.php">
                    <span class="nav-contact__icon"><i class="fa-regular fa-envelope"></i></span>
                    <span class="nav-contact__label">Contact Us</span>
                </a>
                <a class="btn btn-primary" href="appointment.php">Book Appointment</a>
            </nav>
        </div>
    </header>
