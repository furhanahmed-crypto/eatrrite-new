<?php $refer = $cohort['refer']; ?>
<section class="section" aria-labelledby="co-refer-title">
    <div class="container">
        <article class="card co-prose-card co-center">
            <div class="feature-card__icon co-icon-center"><i class="fa-solid fa-gift"></i></div>
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($refer['pill']); ?></span>
            <h2 class="section-title" id="co-refer-title"><?php echo htmlspecialchars($refer['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($refer['text']); ?></p>
        </article>
    </div>
</section>
