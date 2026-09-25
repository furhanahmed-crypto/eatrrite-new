    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <img class="footer-logo" src="<?php echo htmlspecialchars(er_href('assets/images/logo/logo-horizontal-light.png')); ?>" alt="Eat Rrite">
                <p>Science-backed, culturally-rooted nutrition coaching — so you learn how to Eat Rrite for life, without crash diets.</p>
                <p class="footer-locations"><?php echo htmlspecialchars($site['address_1']); ?> &amp; <?php echo htmlspecialchars($site['address_2']); ?><br><small><?php echo htmlspecialchars($site['locations_note']); ?></small></p>
            </div>
            <div>
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo htmlspecialchars(er_href('index.php')); ?>">Home</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('about.php')); ?>">About Us</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('programs.php')); ?>">Programs</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('pricing.php')); ?>">Pricing</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('blog.php')); ?>">Blog</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('appointment.php')); ?>">Appointment</a></li>
                    <li><a href="<?php echo htmlspecialchars(er_href('contact.php')); ?>">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3>Programs</h3>
                <ul>
                    <?php foreach ($programsLive as $program): ?>
                        <li><a href="<?php echo htmlspecialchars(er_href('program.php?slug=' . urlencode($program['slug']))); ?>"><?php echo htmlspecialchars($program['short']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3>Get In Touch</h3>
                <p><a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a></p>
                <p><a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a></p>
                <p><?php echo htmlspecialchars($site['hours']); ?></p>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Eat Rrite. All rights reserved.</span>
            <span>Learn to Eat Rrite.</span>
        </div>
    </footer>

    <div class="float-actions" aria-label="Quick contact">
        <a class="float-action float-action--call" href="<?php echo htmlspecialchars($site['phone_href']); ?>" aria-label="Call <?php echo htmlspecialchars($site['phone']); ?>">
            <i class="fa-solid fa-phone" aria-hidden="true"></i>
        </a>
        <a class="float-action float-action--whatsapp" href="<?php echo htmlspecialchars($site['whatsapp']); ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="<?php echo htmlspecialchars(er_href('assets/js/main.js')); ?>"></script>
    <?php foreach ($extraJs ?? [] as $src): ?>
        <script src="<?php echo htmlspecialchars(er_href($src)); ?>"></script>
    <?php endforeach; ?>
</body>
</html>
