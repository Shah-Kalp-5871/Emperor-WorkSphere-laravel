<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Office Location Settings
    |--------------------------------------------------------------------------
    |
    | Set your office GPS coordinates and the allowed radius (in meters).
    | Employees must be within the radius to punch in or out.
    |
    | To find your office coordinates:
    | 1. Open Google Maps
    | 2. Right-click on your office location
    | 3. Copy the latitude and longitude shown
    |
    */

    'latitude'  => 23.022958280006947,   // <-- Replace with your office latitude
    'longitude' => 72.55636053808941,   // <-- Replace with your office longitude
    'radius'    => 200,       // Allowed radius in meters (e.g. 100 = 100 meters)

];
