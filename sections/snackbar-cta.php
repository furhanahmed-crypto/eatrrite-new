<?php $cta = $snackbar['cta']; ?>
<section class="sb-block sb-block--cream">
    <div class="container">
        <div class="sb-cta">
            <div>
                <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($cta['pill']); ?></span>
                <h2><?php echo htmlspecialchars($cta['title']); ?></h2>
                <p><?php echo htmlspecialchars($cta['text']); ?></p>
            </div>
            <a class="btn btn-accent" href="<?php echo htmlspecialchars(er_href($cta['cta']['href'])); ?>"><?php echo htmlspecialchars($cta['cta']['label']); ?></a>
        </div>
    </div>
</section>
