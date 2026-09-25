<section class="section program-detail">
    <div class="container program-split program-split--flush">
        <div class="program-split__copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($copy['aboutPill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($program['name']); ?></h2>
            <p class="section-lead"><?php echo htmlspecialchars($program['about']); ?></p>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href('appointment.php')); ?>">Get Consultation</a>
        </div>
        <div class="program-split__media">
            <img src="<?php echo htmlspecialchars(er_href($program['image'])); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
        </div>
    </div>
</section>
