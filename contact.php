<?php
$pageTitle = 'Contact Eat Rrite';
$pageDescription = 'Get in touch with Eat Rrite to book your nutrition consultation.';
$currentPage = 'contact';
$bannerTitle = 'Contact Us';
$inner = require __DIR__ . '/constants/inner.php';
$copy = $inner['contact'];
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container contact-grid">
        <article class="card">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($copy['pill']); ?></span>
            <h2 class="section-title"><?php echo htmlspecialchars($copy['title']); ?></h2>
            <p><?php echo htmlspecialchars($copy['text']); ?></p>
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
        </article>
        <article class="card">
            <h3><?php echo htmlspecialchars($copy['enquireTitle']); ?></h3>
            <p><?php echo htmlspecialchars($copy['enquireText']); ?></p>
            <div class="contact-actions">
                <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('appointment.php')); ?>">Get Consultation</a>
                <a class="btn btn-primary" href="<?php echo htmlspecialchars($site['whatsapp']); ?>" target="_blank" rel="noopener">Message on WhatsApp</a>
            </div>
            <ul class="contact-program-list">
                <?php foreach ($programsLive as $program): ?>
                    <li>✓ <?php echo htmlspecialchars($program['short']); ?></li>
                <?php endforeach; ?>
            </ul>
        </article>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
