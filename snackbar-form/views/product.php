<?php
$price = number_format($unit);
?>
<div class="sb-check-product">
    <p class="sb-check-kicker"><span></span> Limited bar</p>
    <div class="sb-check-photo">
        <img src="<?php echo htmlspecialchars(er_href('assets/images/snackbar/hero.png')); ?>" alt="Eat Rrite Snackbar">
        <span class="sb-check-price">₹<?php echo $price; ?></span>
    </div>
    <div class="sb-check-copy">
        <h2>Eat Rrite Snackbar</h2>
        <p>One bar is ₹<?php echo $price; ?>. Choose quantity, add your delivery details, then complete payment. We ship after a successful payment.</p>
    </div>
</div>
