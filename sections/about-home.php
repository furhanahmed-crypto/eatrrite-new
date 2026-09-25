<?php $aboutHome = $home['about']; ?>
<section class="section section-cream about-home-section">
    <div class="container split">
        <div class="about-photos about-photos--next">
            <img class="main" src="<?php echo htmlspecialchars(er_href($aboutHome['image'])); ?>" alt="Mukta Patil">
            <img class="side" src="<?php echo htmlspecialchars(er_href($aboutHome['sideImage'])); ?>" alt="Nourishing bowl">
        </div>
        <div class="about-home-copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($aboutHome['pill']); ?></span>
            <h2 class="section-title split-title"><?php echo htmlspecialchars($aboutHome['title']); ?></h2>
            <p class="section-lead"><?php echo htmlspecialchars($aboutHome['lead']); ?></p>
            <p><?php echo htmlspecialchars($aboutHome['text']); ?></p>
            <blockquote class="founder-quote">
                “<?php echo htmlspecialchars($aboutHome['quote']); ?>”
                <cite><?php echo htmlspecialchars($aboutHome['cite']); ?></cite>
            </blockquote>
            <ul class="about-stats">
                <?php foreach ($aboutHome['stats'] as $stat): ?>
                    <li class="about-stat">
                        <?php if (!empty($stat['href'])): ?>
                            <a class="about-stat__link" href="<?php echo htmlspecialchars($stat['href']); ?>">
                                <strong><?php echo htmlspecialchars($stat['value']); ?></strong>
                                <span><?php echo htmlspecialchars($stat['label']); ?></span>
                            </a>
                        <?php else: ?>
                            <strong><?php echo htmlspecialchars($stat['value']); ?></strong>
                            <span><?php echo htmlspecialchars($stat['label']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href($aboutHome['cta']['href'])); ?>"><?php echo htmlspecialchars($aboutHome['cta']['label']); ?></a>
        </div>
    </div>
</section>
