<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

$payload = cohort_verified_application();
$application = is_array($payload['verified'] ?? null) ? $payload['verified'] : null;
$monthly = number_format((int) cohort_config()['monthly_rupees']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Thank you | Eat Rrite Cohort</title>
  <link rel="stylesheet" href="assets/cohort.css">
</head>
<body class="er-cohort">
  <main class="er-wrap">
    <div class="er-thanks">
      <?php if ($application): ?>
        <h1>Thank you, <?php echo htmlspecialchars((string) $application['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>
          Your ₹<?php echo number_format((int) ($application['amount_rupees'] ?? 0)); ?>
          consultation is confirmed. The first month is complimentary. From next month
          you may continue at ₹<?php echo $monthly; ?> or step away. We will write to you within 48 hours.
        </p>
      <?php else: ?>
        <h1>Thank you</h1>
        <p>No recent cohort application was found on this device.</p>
      <?php endif; ?>
      <p><a href="index.php">Back to the cohort</a></p>
    </div>
  </main>
</body>
</html>
