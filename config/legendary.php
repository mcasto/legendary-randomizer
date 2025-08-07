<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration for Remote Updates
    |--------------------------------------------------------------------------
    |
    | These settings control remote database updates via API endpoints.
    | Set LEGENDARY_API_BASE_URL in your .env file to enable remote mode.
    |
    */

    'api_base_url' => env('LEGENDARY_API_BASE_URL'),
    'update_email' => env('LEGENDARY_UPDATE_EMAIL'),
    'update_password' => env('LEGENDARY_UPDATE_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configure timeouts and retry settings for API requests
    |
    */

    'timeout' => env('LEGENDARY_API_TIMEOUT', 60),
    'retry_attempts' => env('LEGENDARY_API_RETRY_ATTEMPTS', 3),
];
