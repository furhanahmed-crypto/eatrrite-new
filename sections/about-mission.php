<?php
$about = $about ?? require dirname(__DIR__) . '/constants/about.php';
$mission = $about['mission'];
?>
<section class="section about-mission">
    <div class="container">
        <div class="section-heading">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($mission['pill']); ?></span>
            <h2 class="section-title"><?php echo htmlspecialchars($mission['title']); ?></h2>
        </div>
        <div class="about-mission__grid">
            <?php foreach ($mission['cards'] as $card): ?>
                <article class="about-mission__card">
                    <h3><?php echo htmlspecialchars($card['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($card['text'])); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
