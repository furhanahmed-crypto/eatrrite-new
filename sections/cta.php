<?php
$home = $home ?? require dirname(__DIR__) . '/constants/home.php';
$cta = $home['cta'];
?>
<section class="section cta-section">
    <div class="container">
        <div class="cta-box">
            <div class="cta-box__glow"></div>
            <div>
                <span class="pill-label pill-label--on-dark"><span class="pill-dot"></span> <?php echo htmlspecialchars($cta['pill']); ?></span>
                <h2 class="split-title"><?php echo htmlspecialchars($cta['title']); ?></h2>
                <p><?php echo htmlspecialchars($cta['text']); ?></p>
            </div>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href($cta['cta']['href'])); ?>"><?php echo htmlspecialchars($cta['cta']['label']); ?></a>
        </div>
    </div>
</section>
