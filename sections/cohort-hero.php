<?php $hero = $cohort['hero']; ?>
<section class="co-hero">
    <div class="container co-hero__grid">
        <div>
            <span class="pill-label pill-label--hero"><span class="pill-dot"></span> <?php echo htmlspecialchars($hero['pill']); ?></span>
            <h1><?php echo htmlspecialchars($hero['title']); ?></h1>
            <p><?php echo htmlspecialchars($hero['text']); ?></p>
            <div class="sb-actions">
                <a class="btn btn-accent" href="<?php echo htmlspecialchars($hero['primary']['href']); ?>" data-er-open-booking><?php echo htmlspecialchars($hero['primary']['label']); ?></a>
                <a class="btn btn-outline-gold" href="<?php echo htmlspecialchars($hero['secondary']['href']); ?>"><?php echo htmlspecialchars($hero['secondary']['label']); ?></a>
            </div>
            <ul class="co-stats">
                <?php foreach ($hero['stats'] as $stat): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($stat['value']); ?></strong>
                        <span><?php echo htmlspecialchars($stat['label']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="co-hero__photo">
            <img src="<?php echo htmlspecialchars(er_href($hero['image'])); ?>" alt="<?php echo htmlspecialchars($hero['image_alt']); ?>">
        </div>
    </div>
</section>
