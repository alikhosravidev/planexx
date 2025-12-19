<?php
// تنظیمات اولیه
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}
require_once PROJECT_ROOT . '/_components/config.php';

// دریافت عنوان صفحه (اگر تعریف نشده، مقدار پیش‌فرض)
$pageTitle = $pageTitle ?? 'صفحه اصلی';
$fullTitle = $pageTitle . ' | ' . $siteConfig['name'];
?>
<!DOCTYPE html>
<html dir="<?= SITE_DIR ?>" lang="<?= SITE_LANG ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $fullTitle ?></title>
  <meta name="description" content="<?= $siteConfig['description'] ?>">
  
  <!-- Sahel Font - فونت اصلی پروژه -->
  <link href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css" rel="stylesheet">
  
  <!-- Font Awesome - آیکون‌ها -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Force Sahel Font -->
  <style>
    * {
      font-family: 'Sahel', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }
    .fa, .fas, .far, .fal, .fab, [class^="fa-"], [class*=" fa-"] {
      font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Pro', 'Font Awesome 6 Brands' !important;
    }
  </style>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= asset('css/variables.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
  
  <!-- PWA Manifest -->
  <link rel="manifest" href="/app/manifest.json.php">
  <meta name="theme-color" content="#0f172a">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f172a',
            secondary: '#64748b',
            'text-primary': '#0f172a',
            'text-secondary': '#475569',
            'text-muted': '#64748b',
            'bg-primary': '#ffffff',
            'bg-secondary': '#f8fafc',
            'bg-tertiary': '#fafbfc',
            'bg-label': '#f8fafc',
            'border-light': '#f1f5f9',
            'border-medium': '#e2e8f0',
            'border-dark': '#cbd5e1',
          },
          spacing: {
            'xs': '4px',
            'sm': '8px',
            'md': '12px',
            'lg': '16px',
            'xl': '20px',
            '2xl': '24px',
            '3xl': '32px',
            '4xl': '40px',
            '5xl': '80px',
          },
          borderRadius: {
            'sm': '6px',
            'md': '8px',
            'lg': '10px',
            'xl': '12px',
            '2xl': '16px',
            '3xl': '20px',
          },
          fontSize: {
            'xs': '13px',
            'sm': '14px',
            'base': '15px',
            'md': '16px',
            'lg': '18px',
            'xl': '20px',
            '2xl': '24px',
            '3xl': '30px',
            '4xl': '36px',
          },
          lineHeight: {
            'tight': '1.25',
            'snug': '1.375',
            'normal': '1.5',
            'relaxed': '1.625',
            'loose': '1.75',
          },
          boxShadow: {
            'sm': '0 1px 3px rgba(0,0,0,0.04)',
            'md': '0 4px 16px rgba(0,0,0,0.06)',
            'lg': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
            'focus': '0 0 0 3px rgba(15, 23, 42, 0.05)',
            'button': '0 4px 12px rgba(15, 23, 42, 0.15)',
          }
        }
      }
    }
  </script>
</head>

