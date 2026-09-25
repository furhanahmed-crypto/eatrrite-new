<?php

$checkout = 'snackbar-form/index.php';

return [
    'hero' => [
        'pill' => 'Eat Rrite · One perfect bar',
        'title' => 'One bar. The Eat Rrite diet, in your hand.',
        'text' => 'Snackbar is a single nutrition bar built the Eat Rrite way — dates, oats and almonds in a recipe that belongs in a real diet. Steady energy, no crash, no mystery powders. Food as medicine, in one bar you will actually look forward to.',
        'image' => 'assets/images/snackbar/hero.png',
        'image_alt' => 'Eat Rrite Snackbar box with a wholesome date, oat and almond bar',
        'primary' => ['label' => 'Buy now', 'href' => $checkout],
        'secondary' => ['label' => 'See the ingredients', 'href' => '#inside'],
    ],
    'product' => [
        'pill' => 'Inside the bar',
        'title' => 'A complete little diet, not a junk snack',
        'lead' => 'One Snackbar is enough. Whole ingredients Mukta would put on a plate — slow energy, clean sweetness, healthy fat — so the gap between meals does not undo the plan.',
        'items' => [
            ['title' => 'Diet-perfect recipe', 'text' => 'Built to sit beside dal-chawal and idli-dosa, not against them. One bar that respects the Eat Rrite plate.'],
            ['title' => 'Only real food', 'text' => 'Dates, oats and almonds. Nothing you cannot name. Nothing that spikes you and leaves you hungry an hour later.'],
            ['title' => 'Proudly Eat Rrite', 'text' => 'The same food-as-medicine thinking as our programmes — now in a bar you can carry, gift, or keep on the kitchen counter.'],
        ],
    ],
    'ingredients' => [
        'pill' => 'Healthy ingredients',
        'title' => 'Three ingredients. That is the praise.',
        'lead' => 'We did not hide the recipe. Snackbar is dates, oats and almonds — chosen because they earn a place in a healthy Indian diet.',
        'items' => [
            ['name' => 'Dates', 'text' => 'Natural sweetness and minerals, so the bar tastes generous without refined sugar running the show.'],
            ['name' => 'Oats', 'text' => 'Slow fibre that keeps you full and steady — the kind of energy a real diet can trust between meals.'],
            ['name' => 'Almonds', 'text' => 'Healthy fat and crunch. Satiety that feels like food, not a packaged sweet pretending to be wellness.'],
        ],
    ],
    'benefits' => [
        'pill' => 'Why this bar',
        'title' => 'A snack you can praise without a caveat',
        'items' => [
            ['num' => '01', 'title' => 'Fits the Eat Rrite diet', 'text' => 'It does not fight your meals. It fills the honest gap — travel, clinic days, late evenings — without a crash.'],
            ['num' => '02', 'title' => 'Science you can taste', 'text' => 'Whole food first, always. No isolate powders, no unreadable labels, no 30-day gimmick in bar form.'],
            ['num' => '03', 'title' => 'One bar, done beautifully', 'text' => 'We did not launch a flavour wall. We made one bar we would eat ourselves — and recommend to someone we love.'],
        ],
    ],
    'diet' => [
        'pill' => 'Food as medicine',
        'title' => 'Keep your food. Carry the bar.',
        'text' => 'Eat Rrite was never about fearing food. Snackbar is the same promise on the go — a bar that supports the plate you already love, instead of replacing it with a diet you cannot live.',
        'points' => [
            'Between meals, not instead of meals',
            'No crash, no guilt, no starvation logic',
            'Sweetness from dates, not a sugar hit',
            'Made for Hyderabad commutes and Dehradun days alike',
        ],
    ],
    'cta' => [
        'pill' => 'Bring the bar home',
        'title' => 'Taste the Eat Rrite bar.',
        'text' => 'Buy Snackbar online — one diet-perfect bar, packed with the ingredients we trust.',
        'cta' => ['label' => 'Buy now', 'href' => $checkout],
    ],
];
