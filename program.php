<?php
require_once __DIR__ . '/includes/config.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$program = $slug !== '' ? eatrrite_find_program($slug) : null;
if ($program === null) {
    header('Location: programs.php');
    exit;
}

$pageTitle = $program['name'];
$pageDescription = $program['summary'];
$currentPage = 'programs';
$bannerTitle = $program['short'];
$bannerCrumb = 'Program';
$inner = require __DIR__ . '/constants/inner.php';
$copy = $inner['program'];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
include __DIR__ . '/sections/program-about.php';
include __DIR__ . '/sections/program-benefits.php';
include __DIR__ . '/sections/program-packages.php';
include __DIR__ . '/includes/footer.php';
