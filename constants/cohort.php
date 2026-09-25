<?php

$apply = 'cohort-form/apply.php';

return [
    'hero' => [
        'pill' => "10 spots · Women's hormonal health & lifestyle reversal",
        'title' => 'Reverse Your Lifestyle Disease & Hormonal Imbalance — With a Plan Built Around You',
        'text' => 'For women dealing with PCOS, thyroid imbalance, stubborn weight, or early lifestyle disease who are ready to work on the root cause — guided personally by nutritionist Mukta Patil.',
        'image' => 'assets/images/services/hormone.jpg',
        'image_alt' => "Women's hormonal health and lifestyle coaching with Eat Rrite",
        'primary' => ['label' => "Apply for this month's cohort", 'href' => '#book-consultation'],
        'secondary' => ['label' => 'See how it works', 'href' => '#how-it-works'],
        'stats' => [
            ['value' => '8', 'label' => 'Years of Practice'],
            ['value' => '500+', 'label' => 'Lives Transformed'],
            ['value' => '4.8', 'label' => 'Google Rating'],
        ],
    ],
] + require __DIR__ . '/cohort-copy.php';
