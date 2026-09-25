<?php

return [
    [
        'key' => 'current_symptoms',
        'label' => 'Which of these are you experiencing currently?',
        'type' => 'checkbox-groups',
        'required' => true,
        'groups' => [
            'Mind • Mood • Sleep' => [
                'Brain fog or difficulty concentrating',
                'Mood swings, irritability or feeling emotionally overwhelmed',
                'Poor sleep / frequent waking / waking up unrefreshed',
            ],
            'Face • Skin • Hair' => [
                'Puffy face or bags under the eyes',
                'Noticeable changes in skin or hair',
            ],
            'Energy • Appetite • Weight' => [
                'Fatigue despite a full night\'s sleep',
                'Energy crash or sleepiness after meals',
                'Increased hunger, cravings or difficulty feeling satisfied',
                'Unexplained weight gain / difficulty losing weight',
            ],
            'Abdomen • Digestion' => [
                'Increasing belly fat / waistline',
                'Bloating, acidity, gas or indigestion',
            ],
            'Blood Sugar • Metabolic Health' => [
                'Blood sugar fluctuations / insulin resistance / diabetes',
            ],
            'Fluid Balance • Inflammation' => [
                'Puffy hands, feet or face, especially after waking',
                'Feeling persistently heavy, swollen or inflamed',
            ],
        ],
    ],
];
