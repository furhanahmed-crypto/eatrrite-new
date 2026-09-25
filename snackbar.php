<?php
$pageTitle = 'Snackbar | Eat Rrite';
$pageDescription = 'Snackbar by Eat Rrite — one diet-perfect bar of dates, oats and almonds.';
$currentPage = 'snackbar';
$snackbar = require __DIR__ . '/constants/snackbar.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/snackbar-hero.php';
include __DIR__ . '/sections/snackbar-product.php';
include __DIR__ . '/sections/snackbar-ingredients.php';
include __DIR__ . '/sections/snackbar-benefits.php';
include __DIR__ . '/sections/snackbar-diet.php';
include __DIR__ . '/sections/snackbar-cta.php';
include __DIR__ . '/includes/footer.php';
