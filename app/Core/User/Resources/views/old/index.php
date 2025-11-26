<?php
// تنظیمات اولیه
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'صفحات پروتوتایپ';

// لیست تمام صفحات پروتوتایپ
$prototypePages = [
  [
    'title'       => 'صفحه ورود',
    'description' => 'ورود به سیستم با موبایل و کد OTP',
    'url'         => '/auth.php',
    'icon'        => 'fa-solid fa-right-to-bracket',
    'badge'       => 'احراز هویت',
    'badge_color' => 'bg-blue-50 text-blue-700',
  ],
  // در آینده صفحات جدید اینجا اضافه می‌شوند
];

// لود کامپوننت Head
component('head');
?>

<body class="bg-bg-secondary">
  
  <!-- Header -->
  <header class="bg-primary text-white shadow-lg">
    <div class="max-w-[1400px] mx-auto px-8 py-xl">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <i class="fa-solid fa-layer-group text-2xl"></i>
          <div>
            <h1 class="text-xl font-bold leading-snug"><?= $siteConfig['name'] ?></h1>
            <p class="text-sm text-gray-300 leading-normal">پروتوتایپ‌های رابط کاربری</p>
          </div>
        </div>
        <span class="hidden md:inline-flex items-center gap-2 bg-white/10 px-4 py-2 rounded-lg text-sm leading-normal">
          <i class="fa-solid fa-code"></i>
          نسخه <?= $siteConfig['version'] ?>
        </span>
      </div>
    </div>
  </header>
  
  <!-- Main Content -->
  <main class="max-w-[1400px] mx-auto px-8 py-5xl">
    
    <!-- Hero Section -->
    <div class="text-center mb-5xl">
      <h2 class="text-4xl font-bold text-text-primary mb-4 leading-tight">
        صفحات پروتوتایپ
      </h2>
      <p class="text-md text-text-secondary leading-relaxed max-w-2xl mx-auto">
        در این صفحه می‌توانید تمام پروتوتایپ‌های طراحی شده را مشاهده و تست کنید.
        هر کارت شامل لینک مستقیم به صفحه مربوطه است.
      </p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3xl mb-5xl">
      
      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl p-3xl text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
          <i class="fa-solid fa-file-code text-3xl opacity-80"></i>
          <span class="text-4xl font-bold"><?= count($prototypePages) ?></span>
        </div>
        <h3 class="text-lg font-semibold leading-snug">صفحات طراحی شده</h3>
        <p class="text-sm text-blue-100 leading-normal">تعداد کل پروتوتایپ‌ها</p>
      </div>
      
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-3xl p-3xl text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
          <i class="fa-solid fa-palette text-3xl opacity-80"></i>
          <span class="text-4xl font-bold">100%</span>
        </div>
        <h3 class="text-lg font-semibold leading-snug">Tailwind CSS</h3>
        <p class="text-sm text-green-100 leading-normal">طراحی مدرن و مینیمال</p>
      </div>
      
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl p-3xl text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
          <i class="fa-solid fa-mobile-screen text-3xl opacity-80"></i>
          <span class="text-4xl font-bold">✓</span>
        </div>
        <h3 class="text-lg font-semibold leading-snug">Responsive</h3>
        <p class="text-sm text-purple-100 leading-normal">سازگار با موبایل</p>
      </div>
      
    </div>
    
    <!-- Pages Grid -->
    <div>
      <div class="flex items-center justify-between mb-3xl">
        <h3 class="text-2xl font-bold text-text-primary leading-snug">
          لیست صفحات
        </h3>
        <div class="flex items-center gap-2 text-sm text-text-muted">
          <i class="fa-solid fa-info-circle"></i>
          <span class="leading-normal">روی هر کارت کلیک کنید</span>
        </div>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3xl">
        
        <?php foreach ($prototypePages as $page): ?>
        <a href="<?= $page['url'] ?>" 
           class="group bg-bg-primary border border-border-light rounded-3xl p-3xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200">
          
          <!-- Icon & Badge -->
          <div class="flex items-start justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-primary to-gray-700 rounded-2xl flex items-center justify-center text-white text-xl group-hover:scale-110 transition-all duration-200">
              <i class="<?= $page['icon'] ?>"></i>
            </div>
            <span class="inline-flex items-center gap-1.5 <?= $page['badge_color'] ?> px-2.5 py-1 rounded-lg text-xs font-medium uppercase tracking-wide leading-normal">
              <?= $page['badge'] ?>
            </span>
          </div>
          
          <!-- Content -->
          <h4 class="text-xl font-semibold text-text-primary mb-2 leading-snug group-hover:text-primary transition-all duration-200">
            <?= $page['title'] ?>
          </h4>
          <p class="text-base text-text-secondary leading-relaxed mb-4">
            <?= $page['description'] ?>
          </p>
          
          <!-- Footer -->
          <div class="flex items-center justify-between pt-4 border-t border-border-light">
            <span class="text-sm text-text-muted leading-normal">مشاهده صفحه</span>
            <i class="fa-solid fa-arrow-left text-primary group-hover:translate-x-[-4px] transition-all duration-200"></i>
          </div>
          
        </a>
        <?php endforeach; ?>
        
        <!-- Coming Soon Card -->
        <div class="bg-bg-primary border-2 border-dashed border-border-medium rounded-3xl p-3xl flex flex-col items-center justify-center text-center min-h-[280px]">
          <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 text-xl mb-4">
            <i class="fa-solid fa-plus"></i>
          </div>
          <h4 class="text-lg font-semibold text-text-secondary mb-2 leading-snug">
            صفحات بیشتر
          </h4>
          <p class="text-sm text-text-muted leading-normal">
            به زودی صفحات جدید اضافه می‌شوند
          </p>
        </div>
        
      </div>
    </div>
    
    <!-- Info Box -->
    <div class="mt-5xl bg-blue-50 border border-blue-200 rounded-2xl p-3xl">
      <div class="flex items-start gap-3">
        <i class="fa-solid fa-lightbulb text-blue-600 text-2xl"></i>
        <div>
          <h4 class="text-lg font-semibold text-blue-900 mb-2 leading-snug">
            راهنما
          </h4>
          <p class="text-base text-blue-800 leading-relaxed mb-3">
            این پروژه یک پروتوتایپ کامل با استفاده از PHP و Tailwind CSS است. 
            تمام صفحات responsive هستند و از کامپوننت‌های قابل استفاده مجدد بهره می‌برند.
          </p>
          <ul class="space-y-2 text-sm text-blue-700">
            <li class="flex items-center gap-2 leading-normal">
              <i class="fa-solid fa-check-circle"></i>
              <span>طراحی مینیمال و مدرن</span>
            </li>
            <li class="flex items-center gap-2 leading-normal">
              <i class="fa-solid fa-check-circle"></i>
              <span>کامپوننت‌های قابل استفاده مجدد</span>
            </li>
            <li class="flex items-center gap-2 leading-normal">
              <i class="fa-solid fa-check-circle"></i>
              <span>سازگار با تمام مرورگرها و دستگاه‌ها</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    
  </main>
  
  <!-- Footer -->
  <?php component('footer'); ?>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
</body>
</html>
