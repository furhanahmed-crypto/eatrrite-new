<?php
$key = (string) $step['key'];
$type = (string) $step['type'];
$required = !empty($step['required']);
?>
<fieldset class="er-q-step" data-er-q-step="<?php echo (int) $index; ?>" <?php echo $index === 0 ? '' : 'hidden'; ?>>
    <legend><?php echo htmlspecialchars((string) $step['label']); ?></legend>
    <?php if ($type === 'text' || $type === 'number'): ?>
        <input
            class="er-q-input"
            type="<?php echo $type === 'number' ? 'number' : 'text'; ?>"
            name="<?php echo htmlspecialchars($key); ?>"
            <?php if ($type === 'number'): ?>min="<?php echo (int) ($step['min'] ?? 13); ?>" max="<?php echo (int) ($step['max'] ?? 90); ?>" inputmode="numeric"<?php endif; ?>
            <?php echo $required ? 'required' : ''; ?>
            value="<?php echo $key === 'name' ? htmlspecialchars((string) $appointment['name']) : ''; ?>"
        >
    <?php elseif ($type === 'radio'): ?>
        <?php foreach ($step['options'] as $option): ?>
            <label class="er-q-choice">
                <input type="radio" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars((string) $option); ?>" <?php echo $required ? 'required' : ''; ?>>
                <span><?php echo htmlspecialchars((string) $option); ?></span>
            </label>
        <?php endforeach; ?>
    <?php elseif ($type === 'checkbox'): ?>
        <?php foreach ($step['options'] as $option): ?>
            <label class="er-q-choice">
                <input type="checkbox" name="<?php echo htmlspecialchars($key); ?>[]" value="<?php echo htmlspecialchars((string) $option); ?>">
                <span><?php echo htmlspecialchars((string) $option); ?></span>
            </label>
        <?php endforeach; ?>
    <?php elseif ($type === 'checkbox-groups'): ?>
        <?php foreach ($step['groups'] as $group => $options): ?>
            <p class="er-q-group"><?php echo htmlspecialchars((string) $group); ?></p>
            <?php foreach ($options as $option): ?>
                <label class="er-q-choice">
                    <input type="checkbox" name="<?php echo htmlspecialchars($key); ?>[]" value="<?php echo htmlspecialchars((string) $option); ?>">
                    <span><?php echo htmlspecialchars((string) $option); ?></span>
                </label>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($step['follow_key'])): ?>
        <label class="er-q-follow">
            <span><?php echo htmlspecialchars((string) $step['follow_label']); ?></span>
            <textarea class="er-q-input" name="<?php echo htmlspecialchars((string) $step['follow_key']); ?>" rows="4" placeholder="Optional, but helpful"></textarea>
        </label>
    <?php endif; ?>
</fieldset>
