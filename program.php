<?php
require_once __DIR__ . '/includes/config.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$program = $slug !== '' ? eatrrite_find_program($slug) : null;

if ($program === null) {
    header('Location: programs.php');
    exit;
}

$pageTitle = $program['meta_title'];
$pageDescription = $program['meta_description'];
$currentPage = 'programs';
$bannerTitle = $program['short'];
$secondaryImage = $program['image_secondary'] ?? 'assets/images/hero/healthy-plate.jpg';
$founderImage = 'assets/images/about/founder.jpg';

include __DIR__ . '/includes/header.php';
include __DIR__ . '/sections/page-banner.php';
?>
<section class="section program-detail">
    <div class="container">

        <!-- 1. About + program image -->
        <div class="program-split">
            <div class="program-split__copy">
                <span class="pill-label"><span class="pill-dot"></span> About the Program</span>
                <h1 class="section-title split-title"><?php echo htmlspecialchars($program['name']); ?></h1>
                <p class="section-lead"><?php echo htmlspecialchars($program['about']); ?></p>
                <a class="btn btn-accent" href="appointment.php">Get Consultation</a>
            </div>
            <div class="program-split__media">
                <img src="<?php echo htmlspecialchars($program['image']); ?>" alt="<?php echo htmlspecialchars($program['short']); ?>">
            </div>
        </div>

        <!-- 2. Benefits + Ideal For + secondary image -->
        <div class="program-split program-split--reverse">
            <div class="program-split__media">
                <img src="<?php echo htmlspecialchars($secondaryImage); ?>" alt="Nutrition lifestyle for <?php echo htmlspecialchars($program['short']); ?>">
            </div>
            <div class="program-split__copy">
                <h2>Key Benefits</h2>
                <ul class="check-list">
                    <?php foreach ($program['benefits'] as $benefit): ?>
                        <li><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($benefit); ?></li>
                    <?php endforeach; ?>
                </ul>
                <h2>Ideal For</h2>
                <p><?php echo htmlspecialchars($program['ideal']); ?></p>
            </div>
        </div>

        <!-- 3. Mukta + What to Expect + founder image -->
        <div class="program-split">
            <div class="program-split__copy">
                <h2>Working Directly With Mukta</h2>
                <p><?php echo htmlspecialchars($program['mukta']); ?></p>
                <p><a class="program-story-link" href="about.php">Read Mukta's full story →</a></p>
                <h2>What to Expect</h2>
                <p><?php echo htmlspecialchars($program['expect']); ?></p>
                <p class="disclaimer"><?php echo htmlspecialchars($site['results_disclaimer']); ?></p>
            </div>
            <div class="program-split__media program-split__media--founder">
                <img src="<?php echo htmlspecialchars($founderImage); ?>" alt="Mukta Patil, Founder of Eat Rrite">
                <span class="program-founder-caption">Nt. Mukta Patil · Founder</span>
            </div>
        </div>

        <div class="program-packages">
            <div class="section-heading is-center">
                <span class="pill-label"><span class="pill-dot"></span> Packages</span>
                <h2 class="section-title split-title">Choose your program length</h2>
                <p class="section-lead" style="margin-left:auto;margin-right:auto;text-align:center;">Every package begins with a consultation. Pick the length that fits your goal — or let Mukta recommend one on your call.</p>
            </div>
            <div class="program-packages__grid">
                <?php foreach ($programPackages as $package): ?>
                <article class="package-card<?php echo !empty($package['featured']) ? ' is-featured' : ''; ?>">
                    <?php if (!empty($package['featured'])): ?>
                        <span class="package-card__badge">Most chosen</span>
                    <?php endif; ?>
                    <strong><?php echo htmlspecialchars($package['name']); ?></strong>
                    <p><?php echo htmlspecialchars($package['blurb']); ?></p>
                    <a class="btn <?php echo !empty($package['featured']) ? 'btn-accent' : 'btn-primary'; ?>" href="appointment.php">Get Consultation</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="program-process">
            <h2 class="section-title">Our Process</h2>
            <div class="process-grid">
                <?php foreach ($programProcess as $i => $step): ?>
                <article class="process-card">
                    <div class="process-num"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></div>
                    <h3><?php echo htmlspecialchars($step['title']); ?></h3>
                    <p><?php echo htmlspecialchars($step['text']); ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="program-faqs">
            <h2 class="section-title">FAQs</h2>
            <div class="faq-wrap" style="max-width:860px;">
                <?php foreach ($program['faqs'] as $i => $faq): ?>
                <article class="faq-item<?php echo $i === 0 ? ' is-open' : ''; ?>">
                    <button class="faq-question" type="button">
                        <span><?php echo htmlspecialchars($faq['q']); ?></span>
                        <i class="fa-solid fa-chevron-down faq-chevron"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer__inner"><?php echo htmlspecialchars($faq['a']); ?></div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="other-programs">
            <div class="section-heading is-center">
                <span class="pill-label"><span class="pill-dot"></span> Keep Exploring</span>
                <h2 class="section-title">Other Programs</h2>
            </div>
            <div class="other-programs__grid">
                <?php foreach ($programsLive as $other): ?>
                    <?php if ($other['slug'] === $program['slug']) continue; ?>
                    <article class="card program-card">
                        <div class="img-wrap">
                            <img src="<?php echo htmlspecialchars($other['image']); ?>" alt="<?php echo htmlspecialchars($other['short']); ?>">
                        </div>
                        <h3><?php echo htmlspecialchars($other['short']); ?></h3>
                        <p><?php echo htmlspecialchars($other['summary']); ?></p>
                        <a class="program-link" href="program.php?slug=<?php echo urlencode($other['slug']); ?>">View Program <i class="fa-solid fa-arrow-right"></i></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/sections/cta.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
