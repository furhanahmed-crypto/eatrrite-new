<?php
$about = $about ?? require dirname(__DIR__) . '/constants/about.php';
?>
<section class="section section-mint about-mission">
    <div class="container about-mission__grid">
        <?php foreach ($about['cards'] as $card): ?>
            <article class="about-mission__card">
                <h3><?php echo htmlspecialchars($card['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($card['text'])); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
