<?php
$pageTitle = 'Contact Eat Rrite | Book a Nutrition Consultation';
$pageDescription = 'Get in touch with Eat Rrite to book your nutrition consultation — serving clients online across Hyderabad, Dehradun and beyond.';
$currentPage = 'contact';
$bannerTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container contact-grid">
        <div class="card">
            <span class="pill-label"><span class="pill-dot"></span> Get In Touch</span>
            <h1 class="section-title">Let's Start With a Conversation</h1>
            <p>Eat Rrite works with clients entirely online, so wherever you're based, you can book a consultation with Mukta directly.</p>
            <p><strong>Phone / WhatsApp</strong><br>
                <a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a>
            </p>
            <p><strong>Email</strong><br>
                <a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a>
            </p>
            <p><strong>Based in</strong><br>
                <?php echo htmlspecialchars($site['address_1']); ?> &amp; <?php echo htmlspecialchars($site['address_2']); ?><br>
                <span class="muted"><?php echo htmlspecialchars($site['locations_note']); ?></span>
            </p>
            <p><strong>Hours</strong><br><?php echo htmlspecialchars($site['hours']); ?></p>
        </div>
        <div class="card">
            <h3>Book or enquire</h3>
            <p>Share your name, phone, city and program of interest — we'll take it from there.</p>
            <div class="contact-actions">
                <a class="btn btn-accent" href="appointment.php">Book Your Consultation (₹800)</a>
                <a class="btn btn-primary" href="<?php echo htmlspecialchars($site['whatsapp']); ?>" target="_blank" rel="noopener">Message on WhatsApp</a>
            </div>
            <ul class="check-list contact-program-list">
                <?php foreach ($programsLive as $program): ?>
                    <li><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($program['short']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
