<?php $blog = $home['blog']; ?>
<section class="section blog-section" id="blog">
    <div class="container">
        <div class="blog-heading">
            <div>
                <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($blog['pill']); ?></span>
                <h2 class="section-title split-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
            </div>
            <p class="blog-heading__lead"><?php echo htmlspecialchars($blog['lead']); ?></p>
        </div>
        <div class="blog-grid">
            <?php foreach ($blog['posts'] as $post): ?>
            <article class="blog-card">
                <div class="blog-card__media">
                    <img src="<?php echo htmlspecialchars(er_href($post['image'])); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <span class="blog-card__date"><?php echo htmlspecialchars($post['date']); ?></span>
                </div>
                <div class="blog-card__body">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo htmlspecialchars($post['text']); ?></p>
                    <a class="blog-card__link" href="<?php echo htmlspecialchars(er_href('blog.php')); ?>">Read More <span><i class="fa-solid fa-arrow-right"></i></span></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
