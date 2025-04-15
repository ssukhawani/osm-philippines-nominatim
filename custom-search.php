<?php
// Custom search configuration for Nominatim (Philippines-focused)
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
            'coffee' => ['coffee', 'cafe', 'café', 'kape', 'coffee shop', 'coffeeshop'],
            'food' => ['restaurant', 'food', 'kainan', 'karinderia', 'eatery', 'bistro'],
            'hotel' => ['hotel', 'inn', 'resort', 'lodging', 'motel', 'apartment'],
            'tourist_attraction' => ['tourist spot', 'attraction', 'landmark', 'must-see', 'POI'],
            'historic' => ['historic', 'monument', 'heritage', 'shrine', 'dambana'],
            'amenity' => ['hospital', 'school', 'university', 'mall', 'palengke'],
            'tourism' => ['museum', 'gallery', 'zoo', 'theme park', 'liwasan'],
            'leisure' => ['park', 'garden', 'playground', 'plaza', 'picnic area']
        ],
        'importance' => [
            'weight' => [
                'tourist_attraction' => 1.8,  // Highest priority (like Google)
                'historic' => 1.7,
                'leisure' => 1.6,
                'hotel' => 1.5,
                'food' => 1.5,
                'coffee' => 1.4,
                'amenity' => 1.3,
                'tourism' => 1.2
            ],
            'categories' => [
                'tourist_attraction' => [
                    'tourism' => ['attraction', 'museum', 'viewpoint'],
                    'historic' => ['monument', 'memorial']
                ],
                'leisure' => [
                    'leisure' => ['park', 'garden', 'recreation_ground']
                ],
                'hotel' => [
                    'tourism' => ['hotel', 'hostel'],
                    'building' => ['hotel']
                ]
            ]
        ]
    ]
];

// Additional data sources (GeoJSON files for Philippines)
$CONFIG_ADDITIONAL_DATA_SOURCES = [
    'admin-boundaries' => [
        'type' => 'geojson',
        'path' => '/nominatim/data/admin-boundaries.geojson',
        'layer' => 'admin_boundaries',
        'import' => true,
        'use_for_search' => true,
        'use_for_geocoding' => true
    ],
    'philippines-roads' => [
        'type' => 'geojson',
        'path' => '/nominatim/data/philippines-roads.geojson',
        'layer' => 'roads',
        'import' => true,
        'use_for_search' => true,
        'use_for_geocoding' => true
    ]
];

// Priority tuning for Philippine data
$CONFIG_SEARCH_PARAMS = [
    'admin-boundaries' => ['weight' => 0.9, 'importance' => 0.8],
    'philippines-roads' => ['weight' => 0.7, 'importance' => 0.6]
];

// Force Google-like center bias for the Philippines
$CONST_Default_Search_Area = 'Philippines';
$CONST_Default_Lat = 14.5833516;  // Manila area bias
$CONST_Default_Lon = 120.9799471;

// PostgreSQL optimization for geocoding
$CONFIG_DATABASE_SETTINGS = [
    'shared_buffers' => '2GB',
    'work_mem' => '32MB',
    'maintenance_work_mem' => '512MB'
];