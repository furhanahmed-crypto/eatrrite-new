<?php $faq = $cohort['faq']; ?>
<section class="section faq-section" id="faqs" aria-labelledby="co-faq-title">
    <div class="container">
        <div class="faq-panel">
            <div class="faq-layout">
                <div class="faq-intro">
                    <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($faq['pill']); ?></span>
                    <h2 class="section-title split-title" id="co-faq-title"><?php echo htmlspecialchars($faq['title']); ?></h2>
                    <div class="faq-illustration">
                        <img src="<?php echo htmlspecialchars(er_href($faq['image'])); ?>" alt="">
                    </div>
                </div>
                <div class="faq-divider" aria-hidden="true"></div>
                <div class="faq-wrap">
                    <?php foreach ($faq['items'] as $i => $item): ?>
                        <article class="faq-item<?php echo $i === 0 ? ' is-open' : ''; ?>">
                            <button class="faq-question" type="button">
                                <span><?php echo htmlspecialchars($item['q']); ?></span>
                                <i class="fa-solid fa-chevron-down faq-chevron"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer__inner"><?php echo htmlspecialchars($item['a']); ?></div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
