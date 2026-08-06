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

    'green_api' => [
        'api_url' => env('GREENAPI_API_URL', 'https://api.green-api.com'),
        'id_instance' => (string) env('GREENAPI_ID_INSTANCE', ''),
        'api_token' => (string) env('GREENAPI_API_TOKEN', ''),
        'timeout' => (int) env('GREENAPI_TIMEOUT', 20),
        'verify_ssl' => filter_var(env('GREENAPI_VERIFY_SSL', true), FILTER_VALIDATE_BOOL),

        // Приветствие после регистрации клиента (:name — имя из запроса).
        'welcome_enabled' => filter_var(env('GREENAPI_WELCOME_ENABLED', true), FILTER_VALIDATE_BOOL),
        'welcome_message' => env(
            'GREENAPI_WELCOME_MESSAGE',
            ':name, добро пожаловать в ASU Auto! Вы зарегистрированы в программе лояльности.',
        ),
    ],

    'otp' => [
        'length' => (int) env('OTP_LENGTH', 4),
        // Срок жизни кода и интервал между отправками, секунды.
        'ttl' => (int) env('OTP_TTL', 300),
        'resend_interval' => (int) env('OTP_RESEND_INTERVAL', 60),
        'max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 5),
        // Сколько секунд номер считается подтверждённым после ввода кода.
        'verification_ttl' => (int) env('OTP_VERIFICATION_TTL', 1800),
        // :code — сам код, :minutes — срок его действия.
        'message' => env(
            'OTP_MESSAGE',
            'Ваш код подтверждения ASU Auto: :code. Действителен :minutes мин. Никому не сообщайте его.',
        ),
        // Требовать подтверждённый номер в POST /api/createUser.
        'required_on_register' => filter_var(env('OTP_REQUIRED_ON_REGISTER', true), FILTER_VALIDATE_BOOL),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
