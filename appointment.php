<?php
$pageTitle = 'Book an Appointment | Eat Rrite';
$currentPage = 'appointment';
$bannerTitle = 'Book Appointment';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section">
    <div class="container contact-grid">
        <div>
            <p class="eyebrow">Appointment</p>
            <h2>Begin your nutrition journey today</h2>
            <p>Don't miss this opportunity to receive personalized care and expert guidance. Share your details and we will get back to you.</p>
            <p><strong>Call:</strong> <a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a></p>
        </div>
        <div class="card">
            <form class="form-grid" action="mailto:info@eatrrite.com" method="post" enctype="text/plain">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <select name="program" required>
                    <option value="">Select a service</option>
                    <option>Weight & Lifestyle Management Program</option>
                    <option>Gut Health Diet Program</option>
                    <option>Female Hormone Health Diet Program</option>
                    <option>Diabetes Management And Reversal Diet Plan</option>
                    <option>Celiac And Crohn Disease</option>
                    <option>Heart Disease Management Diet Program</option>
                    <option>Oncology Nutrition Program</option>
                    <option>Enduro Sports Nutrition Program</option>
                    <option>NutriCare for Mom-to-Be</option>
                    <option>Post Natal NutriCare and Weight Loss Program</option>
                </select>
                <textarea name="message" rows="4" placeholder="Tell us a little about your goal"></textarea>
                <button class="btn btn-primary" type="submit">Request Appointment</button>
            </form>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
