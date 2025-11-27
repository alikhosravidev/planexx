<?php

declare(strict_types=1);
/**
 * فایل Manifest برای PWA
 * این فایل مشخصات اپلیکیشن را برای نصب به صورت PWA تعریف می‌کند
 */

header('Content-Type: application/json');

echo json_encode([
    'name'             => 'ساپل - سیستم یکپارچه سازمانی',
    'short_name'       => 'ساپل',
    'description'      => 'سیستم یکپارچه درون سازمانی با گیمیفیکیشن و مدیریت تجربه',
    'start_url'        => '/app/index.php',
    'display'          => 'standalone',
    'background_color' => '#ffffff',
    'theme_color'      => '#0f172a',
    'orientation'      => 'portrait',
    'dir'              => 'rtl',
    'lang'             => 'fa',
    'icons'            => [
        [
            'src'     => '/assets/images/icon-192.png',
            'sizes'   => '192x192',
            'type'    => 'image/png',
            'purpose' => 'any maskable',
        ],
        [
            'src'     => '/assets/images/icon-512.png',
            'sizes'   => '512x512',
            'type'    => 'image/png',
            'purpose' => 'any maskable',
        ],
    ],
    'screenshots' => [
        [
            'src'         => '/assets/images/screenshot-1.png',
            'sizes'       => '540x720',
            'type'        => 'image/png',
            'form_factor' => 'narrow',
        ],
    ],
    'categories' => ['business', 'productivity'],
    'shortcuts'  => [
        [
            'name'        => 'خانه',
            'short_name'  => 'خانه',
            'description' => 'صفحه اصلی اپلیکیشن',
            'url'         => '/app/index.php',
            'icons'       => [
                [
                    'src'   => '/assets/images/icon-192.png',
                    'sizes' => '192x192',
                ],
            ],
        ],
        [
            'name'        => 'آمار',
            'short_name'  => 'آمار',
            'description' => 'مشاهده آمار و گزارشات',
            'url'         => '/app/analytics.php',
            'icons'       => [
                [
                    'src'   => '/assets/images/icon-192.png',
                    'sizes' => '192x192',
                ],
            ],
        ],
    ],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
