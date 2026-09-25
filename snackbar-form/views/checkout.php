<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$config = snackbar_config();
$csrf = snackbar_csrf_token();
$unit = (int) $config['amount_rupees'];
$pageTitle = 'Buy Snackbar';
$pageDescription = 'Order Eat Rrite Snackbar. Enter delivery details and pay securely.';
$currentPage = 'snackbar';
$extraCss = ['snackbar-form/assets/snackbar.css', 'snackbar-form/assets/snackbar-form.css'];
include dirname(__DIR__, 2) . '/includes/header.php';
include __DIR__ . '/banner.php';
?>
<section class="sb-check">
    <div class="container sb-check__grid">
        <?php include __DIR__ . '/product.php'; ?>
        <?php include __DIR__ . '/form.php'; ?>
    </div>
</section>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="assets/snackbar.js" defer></script>
<?php include dirname(__DIR__, 2) . '/includes/footer.php'; ?>
