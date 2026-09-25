<?php

return [
    'kicker' => 'Eat Rrite — Transformation Consultation',
    'title' => "You've taken the first step.",
    'lead' => 'This consultation is about understanding what your body is telling you, what may be connecting your concerns, and what needs to change for you to move forward.',
    'note' => 'Answer each question honestly. There are no right or wrong answers.',
    'received' => 'We have received your response.',
    'generating' => 'Generating your Google Meet link…',
    'closing_title' => 'This is your starting point.',
    'closing' => [
        'Your answers give us the first picture.',
        'During your consultation, we will connect the dots between your symptoms, lifestyle, nutrition and health concerns and identify the direction that makes sense for you.',
        'Your transformation starts with understanding your body — not fighting it.',
    ],
    'steps' => array_merge(
        require __DIR__ . '/questionnaire-core.php',
        require __DIR__ . '/questionnaire-symptoms.php',
        require __DIR__ . '/questionnaire-ready.php'
    ),
];
