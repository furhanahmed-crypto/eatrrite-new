<?php
$min = (int) $config['min_quantity'];
$max = (int) $config['max_quantity'];
?>
<div class="sb-check-card">
    <p class="sb-check-card__title">Delivery details</p>
    <form
        class="sb-check-form"
        data-er-snackbar-form
        data-api="api"
        data-csrf="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>"
        data-unit-rupees="<?php echo $unit; ?>"
        data-min-qty="<?php echo $min; ?>"
        data-max-qty="<?php echo $max; ?>"
        novalidate
    >
        <div class="er-alert" data-er-alert hidden></div>
        <div class="sb-check-grid">
            <label class="sb-check-field">
                <span>Full name</span>
                <input name="name" autocomplete="name" maxlength="80" required placeholder="Your name">
            </label>
            <label class="sb-check-field">
                <span>Email</span>
                <input type="email" name="email" autocomplete="email" maxlength="120" required placeholder="you@example.com">
            </label>
        </div>
        <label class="sb-check-field">
            <span>Mobile number</span>
            <input type="tel" name="mobilenumber" inputmode="numeric" maxlength="13" required placeholder="10-digit number">
        </label>
        <label class="sb-check-field">
            <span>Delivery address</span>
            <textarea name="address" rows="4" maxlength="400" required placeholder="House, street, city, PIN"></textarea>
        </label>
        <label class="sb-check-field">
            <span>Quantity</span>
            <div class="sb-check-qty">
                <button type="button" data-er-qty="-1" aria-label="Fewer bars">−</button>
                <input type="number" name="quantity" min="<?php echo $min; ?>" max="<?php echo $max; ?>" value="1" required>
                <button type="button" data-er-qty="1" aria-label="More bars">+</button>
            </div>
        </label>
        <div class="sb-check-total">
            <p data-er-breakdown>₹<?php echo number_format($unit); ?> × 1</p>
            <p>
                <span>Total</span>
                <strong data-er-total>₹<?php echo number_format($unit); ?></strong>
            </p>
        </div>
        <button type="submit" class="sb-check-pay" data-er-submit>Pay ₹<?php echo number_format($unit); ?></button>
    </form>
</div>
