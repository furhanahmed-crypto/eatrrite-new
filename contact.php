<?php
$pageTitle = 'Contact Eat Rrite';
$currentPage = 'contact';
$bannerTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container contact-grid">
        <div class="card">
            <p class="eyebrow">Get In Touch</p>
            <h2>Feel free to contact us</h2>
            <p><strong>Office</strong><br><?php echo htmlspecialchars($site['address_1']); ?><br><?php echo htmlspecialchars($site['address_2']); ?></p>
            <p><strong>Email</strong><br><a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a></p>
            <p><strong>Phone</strong><br><a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a></p>
            <p><strong>Hours</strong><br><?php echo htmlspecialchars($site['hours']); ?></p>
        </div>
        <div class="card">
            <h3>Send a Message</h3>
            <form class="form-grid" action="mailto:info@eatrrite.com" method="post" enctype="text/plain">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="phone" placeholder="Mobile Number" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" rows="5" placeholder="Message"></textarea>
                <button class="btn btn-primary" type="submit">Send Message</button>
            </form>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
