<?php
return [
    'places' => 100,
    'max_consecutive_hour' => 72,
    'parking_rate' => [
        [
            'day' => 'monday',
            'prices' => 3
        ],
        [
            'day' => 'tuesday',
            'prices' => [
                [
                    'from' => '00:00',
                    'to' => '15:00',
                    'price' => 2
                ],
                [
                    'from' => '15:00',
                    'to' => '24:00',
                    'price' => 1
                ]
            ]
        ],
        [
            'day' => 'wednesday',
            'prices' => [
                [
                    'from' => '00:00',
                    'to' => '18:00',
                    'price' => 1
                ],
                [
                    'from' => '18:00',
                    'to' => '24:00',
                    'price' => 3
                ]
            ]
        ],
        [
            'day' => 'thursday',
            'prices' => 3
        ],
        [
            'day' => 'friday',
            'prices' => [
                [
                    'from' => '00:00',
                    'to' => '17:00',
                    'price' => 3
                ],
                [
                    'from' => '17:00',
                    'to' => '24:00',
                    'price' => 5
                ]
            ]
        ],
        [
            'day' => 'saturday',
            'prices' => 5
        ],
        [
            'day' => 'sunday',
            'prices' => [
                [
                    'from' => '00:00',
                    'to' => '19:00',
                    'price' => 5
                ],
                [
                    'from' => '19:00',
                    'to' => '24:00',
                    'price' => 10
                ]
            ]
        ]
    ],
    'opening_hour' => '08:00',
    'closing_hour' => '20:00',
    'price_per_hour' => '1'
];

