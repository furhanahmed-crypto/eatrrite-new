<?php $hero = $home['hero']; ?>
<section class="hero-slider">
    <?php foreach ($hero['slides'] as $i => $slide): ?>
        <div class="hero-slide<?php echo $i === 0 ? ' active' : ''; ?>" style="background-image: url('<?php echo htmlspecialchars(er_href($slide)); ?>');"></div>
    <?php endforeach; ?>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <span class="pill-label pill-label--hero"><span class="pill-dot"></span> <?php echo htmlspecialchars($hero['pill']); ?></span>
            <h1 class="hero-title split-title"><?php echo htmlspecialchars($hero['title']); ?></h1>
            <p class="hero-text"><?php echo htmlspecialchars($hero['text']); ?></p>
            <div class="hero-actions">
                <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href($hero['primaryCta']['href'])); ?>"><?php echo htmlspecialchars($hero['primaryCta']['label']); ?></a>
                <a class="btn btn-outline-light" href="<?php echo htmlspecialchars(er_href($hero['secondaryCta']['href'])); ?>"><?php echo htmlspecialchars($hero['secondaryCta']['label']); ?></a>
            </div>
        </div>
    </div>
</section>
