<?php
require_once __DIR__ . '/appointment-form/bootstrap.php';

$pageTitle = 'Book an Appointment | Eat Rrite';
$currentPage = 'appointment';
$bannerTitle = 'Book Appointment';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section appointment-section">
    <div class="container appointment-layout">
        <div class="appointment-copy">
            <span class="pill-label"><span class="pill-dot"></span> Appointment</span>
                <h2 class="section-title">Begin your nutrition journey today</h2>
            <p>Don't just chase weight loss — bring in holistic wellbeing. Get a consultation and choose a 30, 90 or 180-day program built around your reports and the food you already love.</p>
            <ul class="appointment-points">
                <li><i class="fa-solid fa-check"></i> Personalized consultation with a nutrition specialist</li>
                <li><i class="fa-solid fa-check"></i> Choose your preferred date and time slot</li>
                <li><i class="fa-solid fa-check"></i> Secure confirmation with Razorpay</li>
            </ul>
            <p class="appointment-contact">
                <strong>Prefer to talk first?</strong><br>
                Call <a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a>
                or email <a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a>
            </p>
        </div>
        <div class="appointment-form-card">
            <h3>Make Appointment</h3>
            <?php include __DIR__ . '/appointment-form/form.php'; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
