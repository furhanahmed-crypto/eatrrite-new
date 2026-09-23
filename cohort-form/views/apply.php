<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$config = cohort_config();
$remaining = cohort_service()->remainingSpots();
$csrf = cohort_csrf_token();
$fee = (int) $config['consultation_rupees'];
$monthly = number_format((int) $config['monthly_rupees']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply for the Cohort | Eat Rrite</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600&family=Lora:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/cohort.css">
</head>
<body class="er-cohort">
  <main class="er-wrap">
    <?php if ($remaining <= 0): ?>
      <?php $closedTitle = "This month's cohort is full"; require __DIR__ . '/closed.php'; ?>
    <?php else: ?>
      <p class="er-pill"><?php echo (int) $remaining; ?> spots left</p>
      <h1>Pay the consultation fee. Claim your first month free.</h1>
      <p>A one-time ₹<?php echo number_format($fee); ?> consultation reserves your place — and unlocks a complimentary first month in the ₹<?php echo $monthly; ?> cohort. From month two, continue or step away. No lock-in.</p>
      <div class="er-card">
        <form
          class="er-form"
          data-er-cohort-form
          data-api="api"
          data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
          data-fee-rupees="<?php echo $fee; ?>"
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
          <p class="er-total"><span>Consultation fee</span><span>₹<?php echo number_format($fee); ?></span></p>
          <button type="submit" class="er-submit" data-er-submit>Pay ₹<?php echo number_format($fee); ?> consultation fee</button>
        </form>
      </div>
    <?php endif; ?>
  </main>
  <?php if ($remaining > 0): ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="assets/cohort.js" defer></script>
  <?php endif; ?>
</body>
</html>
