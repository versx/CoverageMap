<?php
$config = [
    // Title of the coverage map page
    "title" => "Map Coverage",
    // Map latitude upon startup
    "startLat" => 0.0,
    // Map longitude upon startup
    "startLon" => 0.0,
    // Map zoom level upon startup
    "startZoom" => 10,
    // Map starting minimum zoom level
    "minZoom" => 10,
    // Map starting maximum zoom level
    "maxZoom" => 18,
    // Custom tileserver url to use for the map
    "tileserver" => "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
    // Areas text to use
    "areasText" => [
        "singular" => "city",
        "plural" => "cities"
    ],
    // Array of coverage areas
    "areas" => [
        [
            // Area name
            "city" => "City1",
            // Custom color or leave blank for random color
            "color" => "black",
            // Area geofence
            "polygons" => [
                // Coordinates pair
                [0.0,0.0]
            ]
        ],
        [
            "city" => "City2",
            "polygons" => [
                [0.1,0.1]
            ]
        ]
    ]
];
?>