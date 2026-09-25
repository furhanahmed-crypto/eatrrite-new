<?php $videos = $cohort['videos']; ?>
<section class="section section-cream testimonials-section" aria-labelledby="co-videos-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($videos['pill']); ?></span>
            <h2 class="section-title split-title" id="co-videos-title"><?php echo htmlspecialchars($videos['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($videos['lead']); ?></p>
        </div>
        <div class="programs-grid">
            <?php for ($i = 0; $i < (int) $videos['count']; $i++): ?>
                <article class="card program-card">
                    <div class="img-wrap co-media-slot co-media-slot--video" aria-hidden="true">
                        <span class="co-play"><i class="fa-solid fa-play"></i></span>
                    </div>
                    <h3><?php echo htmlspecialchars($videos['label']); ?></h3>
                    <p>Video coming soon, shared with the client’s consent.</p>
                </article>
            <?php endfor; ?>
        </div>
    </div>
</section>
