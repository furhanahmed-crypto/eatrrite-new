<?php
$compare = $cohort['compare'];
$mark = static function (string $kind): array {
    return match ($kind) {
        'yes' => ['icon' => 'fa-check', 'class' => 'co-mark--yes', 'label' => 'Yes'],
        'no' => ['icon' => 'fa-xmark', 'class' => 'co-mark--no', 'label' => 'No'],
        'warn' => ['icon' => 'fa-triangle-exclamation', 'class' => 'co-mark--warn', 'label' => 'Limited'],
        default => ['icon' => 'fa-minus', 'class' => 'co-mark--dash', 'label' => 'Not comparable'],
    };
};
?>
<section class="section section-mint" aria-labelledby="co-compare-title">
    <div class="container">
        <div class="section-heading is-center">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($compare['pill']); ?></span>
            <h2 class="section-title split-title" id="co-compare-title"><?php echo htmlspecialchars($compare['title']); ?></h2>
            <p class="section-lead section-lead--center"><?php echo htmlspecialchars($compare['lead']); ?></p>
        </div>
        <p class="co-compare-note">Eat Rrite vs diet apps vs Instagram advice — same problem, very different starting points.</p>
        <div class="co-compare-grid co-swipe" data-co-swipe>
            <?php foreach ($compare['cols'] as $i => $col): ?>
                <article class="feature-card<?php echo !empty($col['brand']) ? ' is-brand' : ''; ?>">
                    <div class="feature-card__icon"><i class="fa-solid <?php echo htmlspecialchars($col['icon']); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($col['title']); ?></h3>
                    <ul class="co-checks">
                        <?php foreach ($compare['rows'] as $row): ?>
                            <?php $m = $mark($row[$i + 1]); ?>
                            <li>
                                <i class="fa-solid <?php echo $m['icon'] . ' ' . $m['class']; ?>" aria-label="<?php echo $m['label']; ?>"></i>
                                <?php echo htmlspecialchars($row[0]); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>