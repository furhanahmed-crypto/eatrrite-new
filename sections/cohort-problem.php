<?php $problem = $cohort['problem']; ?>
<section class="section" aria-labelledby="co-problem-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($problem['pill']); ?></span>
            <h2 class="section-title split-title" id="co-problem-title"><?php echo htmlspecialchars($problem['title']); ?></h2>
        </div>
        <article class="card co-prose-card">
            <p><?php echo htmlspecialchars($problem['text']); ?></p>
            <blockquote class="founder-quote"><?php echo htmlspecialchars($problem['close']); ?></blockquote>
        </article>
    </div>
</section>
