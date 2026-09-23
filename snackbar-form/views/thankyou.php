<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$payload = snackbar_verified_order();
$order = is_array($payload['verified'] ?? null) ? $payload['verified'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Thank you | Eat Rrite Snackbar</title>
  <link rel="stylesheet" href="assets/snackbar.css">
</head>
<body class="er-snackbar">
  <main class="er-wrap">
    <div class="er-thanks">
      <?php if ($order): ?>
        <h1>Thank you, <?php echo htmlspecialchars((string) $order['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>Your payment is confirmed. We will ship <?php echo (int) $order['quantity']; ?> Snackbar<?php echo (int) $order['quantity'] === 1 ? '' : 's'; ?> to your address.</p>
        <p><strong>₹<?php echo number_format((int) ($order['amount_rupees'] ?? 0)); ?></strong> paid.</p>
      <?php else: ?>
        <h1>Thank you</h1>
        <p>No recent Snackbar order was found on this device.</p>
      <?php endif; ?>
      <p><a href="index.php">Buy another bar</a></p>
    </div>
  </main>
</body>
</html>
