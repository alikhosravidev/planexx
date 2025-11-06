<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | AI Image Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for AI image generation services.
    | All providers' settings, defaults, and supported features are defined here.
    |
    */

    'providers' => [
        'openai' => [
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com'),
            'timeout'  => env('OPENAI_TIMEOUT', 120),
            'defaults' => [
                'model'   => env('OPENAI_MODEL', 'dall-e-3'),
                'quality' => env('OPENAI_QUALITY', 'standard'),
            ],
            'supported_features'   => ['text_to_image', 'high_quality', 'style_control'],
            'supported_operations' => ['inpaint', 'variation'],
            'default_size'         => '1024x1024',
        ],

        'stability_ai' => [
            'base_url' => env('STABILITY_BASE_URL', 'https://api.stability.ai'),
            'timeout'  => env('STABILITY_TIMEOUT', 120),
            'defaults' => [
                'engine'    => env('STABILITY_ENGINE', 'stable-diffusion-xl-1024-v1-0'),
                'cfg_scale' => (int) env('STABILITY_CFG_SCALE', 7),
                'steps'     => (int) env('STABILITY_STEPS', 30),
            ],
            'supported_features'   => ['text_to_image', 'high_quality'],
            'supported_operations' => ['inpaint', 'outpaint', 'upscale', 'variation'],
            'default_size'         => '1024x1024',
        ],

        'gemini' => [
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com'),
            'timeout'  => env('GEMINI_TIMEOUT', 120),
            'defaults' => [
                'model'       => env('GEMINI_MODEL', 'gemini-2.5-flash'),
                'temperature' => (float) env('GEMINI_TEMPERATURE', 0.9),
                'top_k'       => (int) env('GEMINI_TOP_K', 40),
                'top_p'       => (float) env('GEMINI_TOP_P', 0.95),
                'max_tokens'  => (int) env('GEMINI_MAX_TOKENS', 8192),
            ],
            'supported_features'   => ['text_to_image'],
            'supported_operations' => ['enhance', 'style_transfer', 'variation'],
            'default_size'         => '1024x1024',
        ],

        'seedream' => [
            'base_url' => env('SEEDREAM_BASE_URL', 'https://api.seedream.ai'),
            'timeout'  => env('SEEDREAM_TIMEOUT', 120),
            'defaults' => [
                'model'          => env('SEEDREAM_MODEL', 'seedream-4'),
                'steps'          => (int) env('SEEDREAM_STEPS', 50),
                'guidance_scale' => (float) env('SEEDREAM_GUIDANCE_SCALE', 7.5),
                'quality'        => env('SEEDREAM_QUALITY', 'high'),
                'sampler'        => env('SEEDREAM_SAMPLER', 'dpmpp_2m'),
            ],
            'supported_features'   => ['text_to_image', 'negative_prompt', 'high_resolution', 'style_presets', 'color_guidance', 'seed_control', 'quality_control', 'fast_generation'],
            'supported_operations' => ['inpaint', 'outpaint', 'upscale', 'variation', 'enhance'],
            'default_size'         => '1024x1024',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Provider Priorities
    |--------------------------------------------------------------------------
    |
    | Default priorities for providers when using createFromEnv()
    |
    */
    'priorities' => [
        'openai'       => 1,
        'stability_ai' => 2,
        'gemini'       => 3,
        'seedream'     => 4,
    ],
];
