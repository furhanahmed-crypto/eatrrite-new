<?php $videos = $cohort['videos']; ?>
<section class="section section-cream testimonials-section" aria-labelledby="co-videos-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($videos['pill']); ?></span>
            <h2 class="section-title split-title" id="co-videos-title"><?php echo htmlspecialchars($videos['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($videos['lead']); ?></p>
        </div>
        <div class="programs-grid co-swipe" data-co-swipe>
            <?php foreach ($videos['items'] as $video): ?>
                <article class="card program-card co-video-card">
                    <div class="co-video">
                        <video
                            src="<?php echo htmlspecialchars(er_href($video['src'])); ?>"
                            controls
                            playsinline
                            preload="metadata"
                            title="<?php echo htmlspecialchars($video['title']); ?>"></video>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
