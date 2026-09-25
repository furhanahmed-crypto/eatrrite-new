<section class="page-banner page-banner--photo">
    <img class="page-banner__bg" src="<?php echo htmlspecialchars(er_href('assets/images/hero/healthy-plate.jpg')); ?>" alt="">
    <div class="page-banner__shade" aria-hidden="true"></div>
    <div class="container page-banner__inner">
        <nav class="page-banner__crumbs" aria-label="Breadcrumb">
            <a href="<?php echo htmlspecialchars(er_href('index.php')); ?>">Home</a>
            <span aria-hidden="true">/</span>
            <span><?php echo htmlspecialchars($bannerCrumb ?? $bannerTitle ?? 'Page'); ?></span>
        </nav>
        <h1><?php echo htmlspecialchars($bannerTitle ?? 'Page'); ?></h1>
    </div>
</section>
