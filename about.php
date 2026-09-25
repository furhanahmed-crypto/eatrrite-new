<?php
$pageTitle = 'About Mukta Patil';
$pageDescription = 'Meet Mukta Patil — nutritionist, speaker and founder of Eat Rrite.';
$currentPage = 'about';
$bannerTitle = 'About Us';
$about = require __DIR__ . '/constants/about.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
include __DIR__ . '/sections/about-story.php';
include __DIR__ . '/sections/about-mission.php';
include __DIR__ . '/includes/footer.php';
