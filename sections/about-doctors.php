<?php
$about = $about ?? require dirname(__DIR__) . '/constants/about.php';
$doctors = $about['doctors'];
?>
<section class="section section-mint about-doctors">
    <div class="container">
        <div class="about-doctors__intro">
            <span class="pill-label"><span class="pill-dot"></span> <?php echo htmlspecialchars($doctors['pill']); ?></span>
            <h2><?php echo htmlspecialchars($doctors['title']); ?></h2>
            <p><?php echo htmlspecialchars($doctors['lead']); ?></p>
        </div>
        <?php foreach ($doctors['people'] as $doctor): ?>
            <article class="about-doctors__card">
                <div class="about-doctors__photo">
                    <img src="<?php echo htmlspecialchars(er_href($doctor['image'])); ?>" alt="<?php echo htmlspecialchars($doctor['name']); ?>">
                </div>
                <div class="about-doctors__copy">
                    <p class="about-doctors__role"><?php echo htmlspecialchars($doctor['designation']); ?></p>
                    <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                    <dl class="about-doctors__meta">
                        <div>
                            <dt>Degree</dt>
                            <dd><?php echo htmlspecialchars($doctor['degree']); ?></dd>
                        </div>
                        <div>
                            <dt>Regd. no.</dt>
                            <dd><?php echo htmlspecialchars($doctor['reg_no']); ?></dd>
                        </div>
                        <div>
                            <dt>Started practice</dt>
                            <dd><?php echo htmlspecialchars($doctor['practice_from']); ?></dd>
                        </div>
                        <div>
                            <dt>Experience</dt>
                            <dd><?php echo htmlspecialchars($doctor['experience']); ?></dd>
                        </div>
                    </dl>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
