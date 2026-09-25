<?php $guide = $cohort['guide']; ?>
<section class="section section-cream about-home-section" aria-labelledby="co-guide-title">
    <div class="container split">
        <div class="about-photos about-photos--next">
            <img class="main" src="<?php echo htmlspecialchars(er_href($guide['image'])); ?>" alt="Mukta Patil, Founder of Eat Rrite">
            <img class="side" src="<?php echo htmlspecialchars(er_href('assets/images/about/bowl.jpg')); ?>" alt="Nourishing bowl">
        </div>
        <div class="about-home-copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($guide['pill']); ?></span>
            <h2 class="section-title split-title" id="co-guide-title"><?php echo htmlspecialchars($guide['title']); ?></h2>
            <p class="section-lead"><?php echo htmlspecialchars($guide['text']); ?></p>
            <ul class="co-checks">
                <?php foreach ($guide['points'] as $point): ?>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> <?php echo htmlspecialchars($point); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
