<div class="er-q-done" data-er-q-done <?php echo $done ? '' : 'hidden'; ?>>
    <div class="er-success__icon" aria-hidden="true">✓</div>
    <h2><?php echo htmlspecialchars((string) $spec['received']); ?></h2>
    <p class="er-q-closing"><?php echo htmlspecialchars((string) $spec['closing'][0]); ?></p>

    <div class="er-meet-link-wrap">
        <div class="er-meet-link-pending" data-er-meet-pending <?php echo $meetLink !== '' ? 'hidden' : ''; ?>>
            <span class="er-spinner" aria-hidden="true"></span>
            <span data-er-meet-copy><?php echo htmlspecialchars((string) $spec['generating']); ?></span>
        </div>
        <a
            class="er-meet-link"
            data-er-meet-link
            target="_blank"
            rel="noopener noreferrer"
            <?php echo $meetLink === '' ? 'hidden' : ''; ?>
            href="<?php echo htmlspecialchars($meetLink, ENT_QUOTES, 'UTF-8'); ?>"
        ><?php echo htmlspecialchars($meetLink); ?></a>
        <p class="er-meet-link-error" data-er-meet-error hidden></p>
    </div>
    <p class="er-success__hint" data-er-email-hint>
        <?php echo $meetLink !== ''
            ? 'A confirmation email with your appointment details and Meet link is on its way.'
            : 'We will email your confirmation once the Meet link is ready.'; ?>
    </p>
</div>
