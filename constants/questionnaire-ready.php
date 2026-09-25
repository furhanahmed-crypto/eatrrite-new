<?php

return [
    [
        'key' => 'symptom_duration',
        'label' => 'How long have you been dealing with these concerns?',
        'type' => 'radio',
        'required' => true,
        'options' => [
            'Less than 3 months',
            '3–6 months',
            '6–12 months',
            '1–3 years',
            'More than 3 years',
        ],
    ],
    [
        'key' => 'attempted_solutions',
        'label' => 'What have you already tried?',
        'type' => 'checkbox',
        'required' => true,
        'follow_key' => 'missing_piece',
        'follow_label' => 'What do you believe has been missing until now?',
        'follow_type' => 'textarea',
        'follow_required' => false,
        'options' => [
            'Diet plans',
            'Intermittent fasting',
            'Calorie restriction',
            'Gym / exercise',
            'Yoga',
            'Supplements',
            'Online programs / apps',
            'Guidance from health professionals',
            'Multiple approaches, but the results did not last',
        ],
    ],
    [
        'key' => 'readiness_level',
        'label' => 'How ready are you to make your health a priority?',
        'type' => 'radio',
        'required' => true,
        'options' => [
            'I am ready to take action and follow through consistently.',
            'I am ready to change, but I need the right structure and accountability.',
            'I am ready to stop experimenting and follow a personalised approach.',
        ],
    ],
    [
        'key' => 'investment_readiness',
        'label' => 'If we determine that a personalised Eat Rrite program is the right fit for you, are you prepared to make the necessary time, effort and financial investment to follow through?',
        'type' => 'radio',
        'required' => true,
        'options' => [
            'Yes. I am ready to invest in getting this right.',
            'Yes. I understand that meaningful health transformation requires a financial, time and personal commitment.',
            'Yes. If I see that the approach is right for me, I am prepared to commit to it.',
        ],
    ],
];
