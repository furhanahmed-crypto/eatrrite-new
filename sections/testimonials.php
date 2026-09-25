<?php $stories = $home['testimonials']; ?>
<section class="section section-cream testimonials-section" id="testimonials">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($stories['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($stories['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($stories['lead']); ?></p>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($stories['items'] as $item): ?>
            <article class="testimonial-card">
                <div class="quote-icon"><i class="fa-solid fa-quote-right"></i></div>
                <img src="<?php echo htmlspecialchars(er_href($item['image'])); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="stars" aria-hidden="true">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p>"<?php echo htmlspecialchars($item['quote']); ?>"</p>
                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                <span class="testimonial-role"><?php echo htmlspecialchars($item['role']); ?></span>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
