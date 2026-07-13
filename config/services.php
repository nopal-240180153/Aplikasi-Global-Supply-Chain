<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | REST Countries
    |--------------------------------------------------------------------------
    */

    'restcountries' => [

        'url' => env('REST_COUNTRIES_BASE_URL'),

        'key' => env('REST_COUNTRIES_API_KEY'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Open Meteo
    |--------------------------------------------------------------------------
    */

    'open_meteo' => [

        'url' => env('OPEN_METEO_BASE_URL'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate API
    |--------------------------------------------------------------------------
    */

    'exchange_rate' => [

        'url' => env('EXCHANGE_RATE_BASE_URL'),

        'key' => env('EXCHANGE_RATE_API_KEY'),

    ],

    'world_bank' => [

    'url' => env('WORLD_BANK_BASE_URL'),

    ],

];