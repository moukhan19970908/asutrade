<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'onec' => [
        'base_url' => env('ONEC_BASE_URL', 'https://oilservice.kz/kacopy/hs/AsuAutoV2'),
        'login' => env('ONEC_LOGIN', 'admin'),
        'password' => env('ONEC_PASSWORD'),
        'timeout' => (int) env('ONEC_TIMEOUT', 20),
        'verify_ssl' => filter_var(env('ONEC_VERIFY_SSL', true), FILTER_VALIDATE_BOOL),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
