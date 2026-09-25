<?php

return [
    [
        'key' => 'name',
        'label' => 'Name',
        'type' => 'text',
        'required' => true,
    ],
    [
        'key' => 'age',
        'label' => 'Age',
        'type' => 'number',
        'required' => true,
        'min' => 13,
        'max' => 90,
    ],
    [
        'key' => 'gender',
        'label' => 'Gender',
        'type' => 'radio',
        'required' => true,
        'options' => ['Female', 'Male'],
    ],
    [
        'key' => 'primary_goal',
        'label' => 'What is the primary change you want to achieve?',
        'type' => 'radio',
        'required' => true,
        'options' => [
            'Lose stubborn weight and reduce belly fat',
            'Understand and manage hormonal / perimenopausal changes',
            'Improve blood sugar / insulin resistance / diabetes',
            'Improve fatty liver / metabolic health',
            'Improve digestion, bloating, acidity or gut health',
            'Improve energy, sleep and recovery',
            'Reduce inflammation, puffiness and feeling of heaviness',
            'Address multiple health concerns together',
        ],
    ],
];
