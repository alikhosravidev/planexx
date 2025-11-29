<?php

declare(strict_types=1);

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
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'auth' => [
        'guard' => 'sanctum',

        'login_limitation_count' => 5,

        'auth_max_attempts' => (int) env('AUTH_MAX_ATTEMPTS', 15),

        'username_validation' => [
            'regex'         => '/^[a-zA-Z][a-zA-Z0-9_]{2,29}$/',
            'error_message' => 'نام کاربری باید با حرف انگلیسی شروع شود و فقط شامل حروف، اعداد و آندرلاین باشد.',
        ],

        'otp' => [
            'enabled'            => env('MAIL_HOST') || env('SMS_API_KEY'),
            'code_length'        => env('SMS_VERIFICATION_CODE_LENGTH', 6),
            'expires_in_minutes' => (int) env('OTP_EXPIRES_IN_MINUTES', 2),
            'max_attempts'       => (int) env('OTP_MAX_ATTEMPTS', 5),

            'sms' => [
                'pattern' => env('SMS_VERIFICATION_CODE_PATTERN_KEY', 'verification-code'),
            ],
        ],

        'password' => [
            'max_attempts'     => (int) env('PASSWORD_MAX_ATTEMPTS', 5),
            'validation_regex' => '^(?=.*[a-z|A-Z])(?=.*[0-9]).{8,}',
        ],

    ],

    'menu' => [
        'cache_enabled' => (bool) env('MENU_CACHE_ENABLED', true),
        'cache_ttl'     => (int) env('MENU_CACHE_TTL', 3600),
        'cache_prefix'  => env('MENU_CACHE_PREFIX', 'menu_'),
    ],

    'stats' => [
        'cache_enabled' => (bool) env('STATS_CACHE_ENABLED', true),
        'cache_ttl'     => (int) env('STATS_CACHE_TTL', 300),
        'cache_prefix'  => env('STATS_CACHE_PREFIX', 'stats_'),
    ],

    'quick_access' => [
        'cache_enabled' => (bool) env('QUICK_ACCESS_CACHE_ENABLED', true),
        'cache_ttl'     => (int) env('QUICK_ACCESS_CACHE_TTL', 3600),
        'cache_prefix'  => env('QUICK_ACCESS_CACHE_PREFIX', 'quick_access_'),
    ],

];
