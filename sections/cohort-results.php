<?php $results = $cohort['results']; ?>
<section class="section section-mint" aria-labelledby="co-results-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($results['pill']); ?></span>
            <h2 class="section-title split-title" id="co-results-title"><?php echo htmlspecialchars($results['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($results['lead']); ?></p>
        </div>
        <div class="programs-grid">
            <?php for ($i = 0; $i < (int) $results['count']; $i++): ?>
                <article class="card program-card">
                    <div class="img-wrap co-media-slot" aria-hidden="true">
                        <i class="fa-regular fa-image"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($results['label']); ?></h3>
                    <p>Photo coming soon, shared with the client’s consent.</p>
                </article>
            <?php endfor; ?>
        </div>
    </div>
</section>
