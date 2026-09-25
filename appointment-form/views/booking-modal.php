<?php

declare(strict_types=1);

$hideService = true;
?>
<div class="er-modal er-booking-modal" id="er-booking-modal" hidden>
    <div class="er-modal__backdrop" data-er-close-booking></div>
    <div class="er-modal__dialog er-booking-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="er-booking-title">
        <header class="er-modal__header">
            <div>
                <p class="er-modal__eyebrow">Consultation</p>
                <h2 id="er-booking-title">Book your slot</h2>
            </div>
            <button type="button" class="er-icon-btn" data-er-close-booking aria-label="Close">×</button>
        </header>
        <p class="er-booking-modal__note">Mukta will recommend the right program on the call. Pay ₹800 to reserve your consultation.</p>
        <?php include __DIR__ . '/form.php'; ?>
    </div>
</div>
<script src="<?php echo htmlspecialchars(appointment_public_path('assets'), ENT_QUOTES, 'UTF-8'); ?>/booking-modal.js?v=<?php echo (int) @filemtime(__DIR__ . '/../assets/booking-modal.js'); ?>" defer></script>
