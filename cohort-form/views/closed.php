<?php

declare(strict_types=1);

$closedTitle = $closedTitle ?? 'This cohort is full';
?>
<section class="er-closed">
  <p class="er-pill">10 spots</p>
  <h1><?php echo htmlspecialchars($closedTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
  <p>
    All places in this cohort have been taken. Email us if you would like to be
    considered for the next one.
  </p>
  <p><a class="er-btn er-btn-gold" href="mailto:info@eatrrite.com">Email us</a></p>
</section>
