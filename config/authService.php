<?php

declare(strict_types=1);

/**
 * Authentication Service Configuration
 * Defines rate limiting and auth behavior
 */

return [
    // Rate limiting configuration
    'auth_max_attempts' => env('AUTH_MAX_ATTEMPTS', '10,1'),

    // OTP expiration in seconds
    'otp_expiration' => env('AUTH_OTP_EXPIRATION', 300),

    // Resend code cooldown in seconds
    'resend_cooldown' => env('AUTH_RESEND_COOLDOWN', 60),

    // Maximum OTP resend attempts
    'max_resend_attempts' => env('AUTH_MAX_RESEND_ATTEMPTS', 5),

    // Session timeout in minutes
    'session_timeout' => env('AUTH_SESSION_TIMEOUT', 60),

    // Token expiration in hours
    'token_expiration' => env('AUTH_TOKEN_EXPIRATION', 24),

    // Enable fingerprinting
    'use_fingerprint' => env('AUTH_USE_FINGERPRINT', true),

    // Enable device tracking
    'track_devices' => env('AUTH_TRACK_DEVICES', true),
];
