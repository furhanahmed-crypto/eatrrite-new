<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$config = snackbar_config();
$csrf = snackbar_csrf_token();
$unit = (int) $config['amount_rupees'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buy Snackbar | Eat Rrite</title>
  <link rel="stylesheet" href="assets/snackbar.css">
</head>
<body class="er-snackbar">
  <main class="er-wrap">
    <p style="letter-spacing:.2em;text-transform:uppercase;font-size:12px;color:#014e4e;">Snackbar</p>
    <h1>Checkout</h1>
    <p>₹<?php echo number_format($unit); ?> per bar. Add quantity and your delivery details, then pay securely.</p>
    <div class="er-card">
      <form
        class="er-form"
        data-er-snackbar-form
        data-api="api"
        data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
        data-unit-rupees="<?php echo $unit; ?>"
        novalidate
      >
        <div class="er-alert" data-er-alert hidden></div>
        <div class="er-grid">
          <label class="er-field">
            <span>Full name</span>
            <input name="name" autocomplete="name" maxlength="80" required placeholder="Your name">
          </label>
          <label class="er-field">
            <span>Email</span>
            <input type="email" name="email" autocomplete="email" maxlength="120" required placeholder="you@example.com">
          </label>
        </div>
        <label class="er-field">
          <span>Mobile number</span>
          <input type="tel" name="mobilenumber" inputmode="numeric" maxlength="13" required placeholder="10-digit number">
        </label>
        <label class="er-field">
          <span>Delivery address</span>
          <textarea name="address" maxlength="400" required placeholder="House, street, city, PIN"></textarea>
        </label>
        <label class="er-field">
          <span>Quantity</span>
          <input type="number" name="quantity" min="<?php echo (int) $config['min_quantity']; ?>" max="<?php echo (int) $config['max_quantity']; ?>" value="1" required>
        </label>
        <p class="er-total"><span>Total</span><span data-er-total>₹<?php echo number_format($unit); ?></span></p>
        <button type="submit" class="er-submit" data-er-submit>Pay ₹<?php echo number_format($unit); ?></button>
      </form>
    </div>
  </main>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script src="assets/snackbar.js" defer></script>
</body>
</html>
