<section class="section program-detail">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($copy['packagesPill']); ?></span>
            <h2 class="section-title"><?php echo htmlspecialchars($copy['packagesTitle']); ?></h2>
        </div>
        <div class="pricing-grid">
            <?php foreach ($programPackages as $package): ?>
            <article class="card pricing-card<?php echo !empty($package['featured']) ? ' is-featured' : ''; ?>">
                <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                <p><?php echo htmlspecialchars($package['blurb']); ?></p>
                <a class="btn btn-primary" href="<?php echo htmlspecialchars(er_href('appointment.php')); ?>">Get Consultation</a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
