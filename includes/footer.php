    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <img src="assets/images/logo/logo-horizontal.png" alt="Eat Rrite" style="height:52px;margin-bottom:16px;">
                <p>Eat Rrite helps you strike a balance between comfort, celebration, food and fitness so healthy living becomes an effortless routine.</p>
            </div>
            <div>
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="index.php#services">Services</a></li>
                    <li><a href="appointment.php">Appointment</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3>Programs</h3>
                <ul>
                    <li>Weight & Lifestyle Management</li>
                    <li>Gut Health</li>
                    <li>Female Hormone Health</li>
                    <li>Diabetes Management</li>
                    <li>Mom-to-Be NutriCare</li>
                </ul>
            </div>
            <div>
                <h3>Get In Touch</h3>
                <p><?php echo htmlspecialchars($site['address_1']); ?><br><?php echo htmlspecialchars($site['address_2']); ?></p>
                <p><a href="<?php echo htmlspecialchars($site['email_href']); ?>"><?php echo htmlspecialchars($site['email']); ?></a></p>
                <p><a href="<?php echo htmlspecialchars($site['phone_href']); ?>"><?php echo htmlspecialchars($site['phone']); ?></a></p>
                <div class="socials">
                    <a href="<?php echo htmlspecialchars($site['instagram']); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="<?php echo htmlspecialchars($site['facebook']); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="<?php echo htmlspecialchars($site['linkedin']); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>&copy; <?php echo date('Y'); ?> Eat Rrite. All rights reserved.</span>
            <span>Stay healthy, stay fit, and just Eat Rrite.</span>
        </div>
    </footer>

    <a class="whatsapp-float" href="<?php echo htmlspecialchars($site['whatsapp']); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>

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
