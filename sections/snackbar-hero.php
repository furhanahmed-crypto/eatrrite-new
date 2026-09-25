<?php $hero = $snackbar['hero']; ?>
<section class="sb-hero">
    <div class="container sb-hero__grid">
        <div class="sb-hero__copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($hero['pill']); ?></span>
            <h1><?php echo htmlspecialchars($hero['title']); ?></h1>
            <p><?php echo htmlspecialchars($hero['text']); ?></p>
            <div class="sb-actions">
                <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href($hero['primary']['href'])); ?>"><?php echo htmlspecialchars($hero['primary']['label']); ?></a>
                <a class="btn btn-outline-gold" href="<?php echo htmlspecialchars($hero['secondary']['href']); ?>"><?php echo htmlspecialchars($hero['secondary']['label']); ?></a>
            </div>
        </div>
        <div class="sb-hero__photo">
            <img src="<?php echo htmlspecialchars(er_href($hero['image'])); ?>" alt="<?php echo htmlspecialchars($hero['image_alt']); ?>">
        </div>
    </div>
</section>
