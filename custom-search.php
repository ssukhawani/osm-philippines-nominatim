<?php
// Custom search configuration for Philippines
return [
    'search' => [
        'default-language' => 'en',
        'languages' => ['en', 'fil'],
        'address-details' => [
            'country' => 'Philippines',
            'state' => true,
            'county' => true,
            'city' => true,
            'suburb' => true,
            'road' => true,
            'house_number' => true,
            'postcode' => true
        ],
        'special-phrases' => [
            'tourist_attraction' => ['tourist', 'attraction', 'landmark'],
            'historic' => ['historic', 'monument', 'heritage'],
            'amenity' => ['restaurant', 'hotel', 'hospital', 'school', 'university'],
            'tourism' => ['museum', 'gallery', 'zoo', 'theme_park'],
            'leisure' => ['park', 'garden', 'sports_centre', 'stadium']
        ],
        'importance' => [
            'weight' => [
                'tourism' => 1.5,
                'historic' => 1.4,
                'amenity' => 1.3,
                'leisure' => 1.2
            ]
        ]
    ]
]; 