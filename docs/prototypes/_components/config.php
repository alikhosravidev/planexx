<?php
/**
 * تنظیمات مرکزی پروژه ساپل
 * این فایل در ابتدای هر صفحه include می‌شود
 */

// جلوگیری از دسترسی مستقیم
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__));
}

// مسیرهای اصلی
define('COMPONENTS_PATH', PROJECT_ROOT . '/_components/');
define('ASSETS_PATH', '/assets/');
define('DATA_PATH', PROJECT_ROOT . '/data/');

// تنظیمات زبان
define('SITE_LANG', 'fa');
define('SITE_DIR', 'rtl');

// اطلاعات پروژه
$siteConfig = [
  'name'        => 'ساپل',
  'description' => 'سیستم یکپارچه درون سازمانی ساپل',
  'version'     => '1.0.0',
];

// توابع کمکی برای کامپوننت‌ها
function component($name, $data = [])
{
    global $siteConfig;
    extract($data);
    $componentPath = COMPONENTS_PATH . $name . '.php';

    if (file_exists($componentPath)) {
        include $componentPath;
    } else {
        echo "<!-- Component '$name' not found -->";
    }
}

function asset($path)
{
    return ASSETS_PATH . ltrim($path, '/');
}

// تابع کمکی برای لود JSON
function loadJson($filename)
{
    $filePath = DATA_PATH . $filename;

    if (file_exists($filePath)) {
        return json_decode(file_get_contents($filePath), true);
    }

    return null;
}
?>

