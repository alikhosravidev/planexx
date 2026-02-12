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

        'login_limitation_count' => 2,

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

    'registry' => [
        'cache_enabled' => (bool) env('REGISTRY_CACHE_ENABLED', true),
        'cache_ttl'     => (int) env('REGISTRY_CACHE_TTL', 3600),
    ],

    'sms' => [
        'provider'                   => env('SMS_PROVIDER', 'kavenegar'),
        'api_key'                    => env('SMS_API_KEY'),
        'sender'                     => env('SMS_SENDER'),
        'pattern'                    => env('SMS_VERIFICATION_CODE_PATTERN_KEY', 'verification-code'),
        'verification_code_length'   => env('SMS_VERIFICATION_CODE_LENGTH', 6),
        'check_code_command_enabled' => env('SMS_CHECK_CODE_COMMAND_ENABLED', env('APP_ENV') === 'production'),
        'variable_number_limit'      => [
            'maximum'   => env('SMS_VARIABLE_NUMBER_LIMIT_MAXIMUM', 5),
            'sms_ir'    => env('SMS_VARIABLE_NUMBER_LIMIT_SMS_IR', 5),
            'kavenegar' => env('SMS_VARIABLE_NUMBER_LIMIT_KAVENEGAR', 3),
        ],
    ],

];
