    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <img class="footer-logo" src="assets/images/logo/logo-horizontal-light.png" alt="Eat Rrite">
                <p>Science-backed, culturally-rooted nutrition coaching — so you learn how to Eat Rrite for life, without crash diets.</p>
                <p class="footer-locations"><?php echo htmlspecialchars($site['address_1']); ?> &amp; <?php echo htmlspecialchars($site['address_2']); ?><br><small><?php echo htmlspecialchars($site['locations_note']); ?></small></p>
            </div>
            <div>
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="programs.php">Programs</a></li>
                    <li><a href="pricing.php">Pricing</a></li>
                    <li><a href="index.php#faqs">FAQs</a></li>
                    <li><a href="appointment.php">Appointment</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3>Programs</h3>
                <ul>
                    <?php foreach ($programsLive as $program): ?>
                        <li><a href="program.php?slug=<?php echo urlencode($program['slug']); ?>"><?php echo htmlspecialchars($program['short']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3>Get In Touch</h3>
                <p><a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a></p>
                <p><a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a></p>
                <p><?php echo htmlspecialchars($site['hours']); ?></p>
                <div class="socials">
                    <a href="<?php echo htmlspecialchars($site['instagram']); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="<?php echo htmlspecialchars($site['facebook']); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="<?php echo htmlspecialchars($site['linkedin']); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
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

    <!-- Video modal (intentional invalid YouTube id so error UI shows) -->
    <div class="video-modal" id="videoModal" aria-hidden="true" role="dialog" aria-labelledby="videoModalTitle">
        <div class="video-modal__backdrop js-close-video"></div>
        <div class="video-modal__dialog" role="document">
            <button type="button" class="video-modal__close js-close-video" aria-label="Close video">&times;</button>
            <h2 id="videoModalTitle" class="video-modal__title">Watch Video</h2>
            <div class="video-modal__frame">
                <iframe
                    class="video-modal__iframe"
                    id="videoFrame"
                    data-src="https://www.youtube.com/embed/INVALID_EATRRITE_VIDEO_ID"
                    src=""
                    title="Eat Rrite video"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
                <div class="video-modal__error" id="videoError" hidden>
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <h3>Video unavailable</h3>
                    <p>This video can’t be played right now. The link may be invalid or the video was removed.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
