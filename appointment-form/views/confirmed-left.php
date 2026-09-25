<aside class="er-q-summary">
    <p class="pill-label"><span class="pill-dot"></span> Payment received</p>
    <h1>Appointment Confirmed</h1>
    <?php if (!empty($appointment['meet_link'])): ?>
        <p data-er-q-left>Your questionnaire is in and your Google Meet link is ready. We have also emailed the details to you.</p>
    <?php elseif (!empty($appointment['questionnaire_completed'])): ?>
        <p data-er-q-left>Thank you. We have your responses and are generating your Google Meet link.</p>
    <?php else: ?>
        <p data-er-q-left>Please complete this questionnaire before we can generate your Google Meet link. Your slot is already reserved.</p>
    <?php endif; ?>
    <dl>
        <div>
            <dt>Name</dt>
            <dd><?php echo htmlspecialchars((string) $appointment['name']); ?></dd>
        </div>
        <div>
            <dt>Consultation</dt>
            <dd><?php echo htmlspecialchars((string) $appointment['service']); ?></dd>
        </div>
        <div>
            <dt>Date</dt>
            <dd><?php echo htmlspecialchars((string) ($appointment['display_date'] ?: $appointment['date'])); ?></dd>
        </div>
        <div>
            <dt>Time</dt>
            <dd><?php echo htmlspecialchars((string) ($appointment['display_time'] ?: $appointment['time'])); ?></dd>
        </div>
    </dl>
</aside>
