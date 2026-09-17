<?php

declare(strict_types=1);

/**
 * Shared success UI for appointment booking confirmation.
 */
?>
<div class="er-success" data-er-success>
    <div class="er-success__icon" aria-hidden="true">✓</div>
    <h3>Appointment confirmed</h3>
    <p data-er-success-copy></p>
    <div class="er-meet-link-wrap" data-er-meet-wrap>
        <div class="er-meet-link-pending" data-er-meet-pending>
            <span class="er-spinner" aria-hidden="true"></span>
            <span>Generating Google Meet link… This may take a couple of minutes.</span>
        </div>
        <a class="er-meet-link" data-er-meet-link target="_blank" rel="noopener noreferrer" hidden></a>
        <p class="er-meet-link-error" data-er-meet-error hidden></p>
    </div>
    <p class="er-success__hint" data-er-email-hint>We will email your confirmation once your Meet link is ready.</p>
</div>
