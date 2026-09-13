<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Auth custom configuration
    |--------------------------------------------------------------------------
    |
    | Shorter TTL for access tokens and a refresh TTL.
    */
    'ttl' => env('JWT_TTL', 60), // access token in minutes (default 60 => 1 hour)
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // 14 days

    // other jwt config passthroughs if needed by package
];
