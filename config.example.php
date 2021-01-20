<?php
$config = [
    "title" => "Map Coverage", //Title of the coverage map page
    "startLat" => 0.0,         //Starting latitude
    "startLon" => 0.0,         //Starting longitude
    "startZoom" => 10,         //Starting zoom
    "minZoom" => 10,           //Minimum zoom level
    "maxZoom" => 18,           //Maximum zoom level
    "tileserver" => "https://tile.openstreetmap.org/{z}/{x}/{y}.png", //Custom tileserver url to use for the map
    "areas" => [               //Array of coverage areas
        [
            "city" => "City1", //Area name
            "color" => "black", //Custom color or leave blank for random color
            "polygons" => [    //Area geofence
                [0.0,0.0]      //Coordinates pair
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