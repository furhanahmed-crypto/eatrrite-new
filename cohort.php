<?php
require_once __DIR__ . '/appointment-form/bootstrap.php';

$pageTitle = 'Lifestyle Reversal Cohort | Eat Rrite';
$pageDescription = "Apply for Eat Rrite's women's hormonal health and lifestyle reversal cohort. ₹800 consultation. 10 spots.";
$currentPage = 'cohort';
$bodyClass = 'co-page';
$extraCss = ['assets/css/cohort.css', 'assets/css/cohort-ui.css'];
$extraJs = ['assets/js/cohort.js?v=' . (int) @filemtime(__DIR__ . '/assets/js/cohort.js')];
$cohort = require __DIR__ . '/constants/cohort.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/cohort-hero.php';
include __DIR__ . '/sections/cohort-fit.php';
include __DIR__ . '/sections/cohort-compare.php';
include __DIR__ . '/sections/cohort-guide.php';
include __DIR__ . '/sections/cohort-consult.php';
include __DIR__ . '/sections/cohort-videos.php';
include __DIR__ . '/sections/cohort-investment.php';
include __DIR__ . '/sections/cohort-faq.php';
include __DIR__ . '/sections/cohort-cta.php';
include __DIR__ . '/sections/cohort-sticky.php';
include __DIR__ . '/appointment-form/views/booking-modal.php';
include __DIR__ . '/includes/footer.php';
