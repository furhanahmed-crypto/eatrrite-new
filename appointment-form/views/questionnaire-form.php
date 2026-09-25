<?php
$done = !empty($appointment['questionnaire_completed']);
$meetLink = trim((string) ($appointment['meet_link'] ?? ''));
$stepCount = count($spec['steps']);
?>
<div
    id="er-q-app"
    class="er-q-card"
    data-api="<?php echo htmlspecialchars($apiBase, ENT_QUOTES, 'UTF-8'); ?>"
    data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
    data-appointment-id="<?php echo htmlspecialchars((string) $appointment['id'], ENT_QUOTES, 'UTF-8'); ?>"
    data-done="<?php echo $done ? '1' : '0'; ?>"
    data-meet-link="<?php echo htmlspecialchars($meetLink, ENT_QUOTES, 'UTF-8'); ?>"
>
    <?php if (!$done): ?>
        <form id="er-q-form" class="er-q-form" novalidate>
            <p class="er-q-kicker"><?php echo htmlspecialchars((string) $spec['kicker']); ?></p>
            <p class="er-q-lead"><?php echo htmlspecialchars((string) $spec['lead']); ?></p>
            <p class="er-q-note"><?php echo htmlspecialchars((string) $spec['note']); ?></p>

            <div class="er-q-progress" aria-live="polite">
                <div class="er-q-progress__meta">
                    <span data-er-q-label>Question 1 of <?php echo $stepCount; ?></span>
                    <span data-er-q-remain><?php echo $stepCount - 1; ?> remaining</span>
                </div>
                <div class="er-q-progress__row">
                    <div class="er-q-progress__track" role="progressbar" aria-valuemin="0" aria-valuemax="<?php echo $stepCount; ?>" aria-valuenow="1">
                        <span data-er-q-bar></span>
                    </div>
                    <ol class="er-q-dots">
                        <?php for ($i = 0; $i < $stepCount; $i++): ?>
                            <li data-er-q-dot="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'is-current' : ''; ?>"><?php echo $i + 1; ?></li>
                        <?php endfor; ?>
                    </ol>
                </div>
            </div>

            <div class="er-alert" data-er-q-alert hidden></div>

            <?php foreach ($spec['steps'] as $index => $step): ?>
                <?php include __DIR__ . '/questionnaire-fields.php'; ?>
            <?php endforeach; ?>

            <div class="er-q-nav">
                <button type="button" class="er-btn-ghost" data-er-q-prev hidden>Back</button>
                <button type="button" class="er-btn-primary" data-er-q-next>Continue</button>
                <button type="submit" class="er-btn-primary" data-er-q-submit hidden>Submit questionnaire</button>
            </div>
        </form>
    <?php endif; ?>

    <?php include __DIR__ . '/questionnaire-done.php'; ?>
</div>
