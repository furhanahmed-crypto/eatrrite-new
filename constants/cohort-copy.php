<?php

$apply = $apply ?? 'cohort-form/apply.php';

return [
    'problem' => [
        'pill' => 'The pattern',
        'title' => 'Sound Familiar?',
        'text' => 'Periods that show up whenever they feel like it. A doctor who says “just lose weight and it’ll sort itself out.” A diet that worked for two weeks, then didn’t. You try, you slip, you restart — and the weight, the fatigue, the cravings all come right back.',
        'close' => 'It was never a willpower problem. It’s that hormonal imbalance and lifestyle disease need a real, personalised reversal plan — not another generic diet chart.',
    ],
    'guide' => [
        'pill' => 'Your Guide Through the Program',
        'title' => 'Meet Mukta Patil — Nutritionist & Founder, Eat Rrite',
        'text' => 'Mukta didn’t set out to become a nutritionist — she became one because she had to. Eat Rrite began from her own health crisis: postpartum weight gain, crash diets, and body-shaming that failed her. Eight years on, her mission is unchanged — helping women heal, in mind and body, without fear, judgment, or fads.',
        'image' => 'assets/images/about/founder.jpg',
        'points' => [
            '8 years in practice across Hyderabad & Dehradun',
            '500+ clients guided through sustainable transformation',
            'Focus areas: hormonal health, diabetes reversal, weight & lifestyle management',
            'Science-backed, culturally-rooted coaching — food you already eat, not a fad diet',
        ],
    ],
    'fit' => require __DIR__ . '/cohort-fit.php',
    'investment' => [
        'pill' => 'An investment, not an expense',
        'title' => 'One consultation. A clearer way forward.',
        'text' => '₹800 is a single conversation with Mukta about your hormones, weight, and energy. You leave knowing what is driving the symptoms and what to change first. That is money put toward getting well — not another month spent managing the same problem.',
        'cta' => ['label' => 'Book Your Consultation', 'href' => '#book-consultation'],
    ],
    'compare' => require __DIR__ . '/cohort-compare.php',
    'get' => require __DIR__ . '/cohort-get.php',
    'results' => [
        'pill' => 'Client stories',
        'title' => 'Real Women, Real Results',
        'lead' => 'Before-and-after photos from women in the reversal cohort — added here with each client’s consent.',
        'count' => 6,
        'label' => 'Client photo',
    ],
    'videos' => [
        'pill' => 'Testimonials',
        'title' => 'Hear From Our Clients',
        'lead' => 'Short video stories from women who worked with Mukta on hormones, weight and lifestyle.',
        'items' => [
            ['src' => 'assets/videos/cohort/3hQAWKcZEgQ.mp4', 'title' => 'Client story'],
            ['src' => 'assets/videos/cohort/DHrqSjeq3HI.mp4', 'title' => 'Client story'],
            ['src' => 'assets/videos/cohort/4mAqFJYEptw.mp4', 'title' => 'Client story'],
        ],
    ],
    'steps' => require __DIR__ . '/cohort-steps.php',
    'faq' => require __DIR__ . '/cohort-faq.php',
    'refer' => [
        'pill' => 'Share the program',
        'title' => 'Refer a friend who joins',
        'text' => 'You both get 7 extra days added — a 90-day program becomes 97 days, on us. Plus, every member gets access to the Eat Rrite community.',
    ],
    'cta' => [
        'pill' => 'Only 10 spots this cohort',
        'title' => 'Book your consultation today',
        'text' => 'Bookings close on the 5th. Pay ₹800 now — hear from Mukta within 48 hours.',
        'cta' => ['label' => 'Book Your Consultation', 'href' => '#book-consultation'],
    ],
    'sticky' => [
        'text' => '10 spots this cohort · Bookings close the 5th',
        'cta' => ['label' => 'Book Consultation — ₹800', 'href' => '#book-consultation'],
    ],
];
