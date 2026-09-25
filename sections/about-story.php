<?php
$about = $about ?? require dirname(__DIR__) . '/constants/about.php';
$story = $about['story'];
?>
<section class="section about-story">
    <div class="container about-story__grid">
        <div class="about-story__photo">
            <img src="<?php echo htmlspecialchars(er_href($story['image'])); ?>" alt="Mukta Patil">
        </div>
        <div class="about-story__copy">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($story['pill']); ?></span>
            <h2><?php echo htmlspecialchars($story['title']); ?></h2>
            <?php foreach ($story['paragraphs'] as $paragraph): ?>
                <p><?php echo htmlspecialchars($paragraph); ?></p>
            <?php endforeach; ?>
        </div>
    </div>
</section>
