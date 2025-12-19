<?php
/**
 * صفحه محتوای شخصی‌سازی شده
 * نمایش محتوا بر اساس علایق و تگ‌های کاربر
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle  = 'محتوای من';
$currentTab = 'personalized';

// تگ‌های انتخابی کاربر (از فرایند آنبوردینگ)
$userInterests = [
    'مدیریت پروژه',
    'بهینه‌سازی',
    'فروش',
    'مشتری‌مداری',
    'تولید',
];

// اخبار مرتبط با علایق کاربر
$personalizedNews = [
    [
        'title'    => 'استراتژی‌های جدید مدیریت پروژه در سال ۱۴۰۳',
        'excerpt'  => 'بهترین روش‌های مدیریت پروژه که باید بدانید',
        'tags'     => ['مدیریت پروژه', 'استراتژی'],
        'image'    => 'https://picsum.photos/seed/news1/400/200',
        'date'     => 'امروز',
        'readTime' => '۵ دقیقه',
        'category' => 'news',
    ],
    [
        'title'    => 'چگونه فرآیند فروش خود را بهینه کنیم؟',
        'excerpt'  => 'تکنیک‌های اثبات شده برای افزایش نرخ تبدیل',
        'tags'     => ['فروش', 'بهینه‌سازی'],
        'image'    => 'https://picsum.photos/seed/news2/400/200',
        'date'     => 'دیروز',
        'readTime' => '۷ دقیقه',
        'category' => 'news',
    ],
];

// تجربیات مرتبط
$personalizedExperiences = [
    [
        'title'      => 'کاهش ۴۰٪ زمان تحویل پروژه',
        'author'     => 'علی محمدی',
        'department' => 'پروژه',
        'tags'       => ['مدیریت پروژه', 'بهینه‌سازی'],
        'date'       => '۲ روز پیش',
        'likes'      => '۲۴',
        'comments'   => '۸',
    ],
    [
        'title'      => 'افزایش رضایت مشتری با تکنیک‌های جدید',
        'author'     => 'زهرا احمدی',
        'department' => 'فروش',
        'tags'       => ['مشتری‌مداری', 'فروش'],
        'date'       => '۳ روز پیش',
        'likes'      => '۳۲',
        'comments'   => '۱۲',
    ],
    [
        'title'      => 'بهبود کیفیت تولید با سیستم جدید QC',
        'author'     => 'حسین رضایی',
        'department' => 'تولید',
        'tags'       => ['تولید', 'بهینه‌سازی'],
        'date'       => '۴ روز پیش',
        'likes'      => '۱۸',
        'comments'   => '۵',
    ],
];

// آموزش‌های پیشنهادی
$recommendedTrainings = [
    [
        'title'      => 'دوره جامع مدیریت پروژه Agile',
        'duration'   => '۳ ساعت',
        'level'      => 'متوسط',
        'instructor' => 'دکتر کریمی',
        'progress'   => 0,
        'thumbnail'  => 'https://picsum.photos/seed/training1/400/240',
    ],
    [
        'title'      => 'تکنیک‌های فروش حرفه‌ای',
        'duration'   => '۲ ساعت',
        'level'      => 'مقدماتی',
        'instructor' => 'مریم احمدی',
        'progress'   => 30,
        'thumbnail'  => 'https://picsum.photos/seed/training2/400/240',
    ],
];

// اطلاعیه‌های مرتبط
$personalizedAnnouncements = [
    [
        'title'        => 'وبینار رایگان: بهینه‌سازی فرآیندهای کاری',
        'date'         => 'پنجشنبه ۱۵ آذر',
        'time'         => '۱۶:۰۰',
        'type'         => 'webinar',
        'isRegistered' => false,
    ],
    [
        'title'        => 'کارگاه عملی: ابزارهای مدیریت پروژه',
        'date'         => 'شنبه ۱۷ آذر',
        'time'         => '۱۰:۰۰',
        'type'         => 'workshop',
        'isRegistered' => true,
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative pb-20">

    <!-- Header -->
    <div class="bg-gradient-to-br from-purple-900 to-purple-700 px-5 pt-8 pb-6 rounded-b-[32px] shadow-lg sticky top-0 z-40">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-white text-xl font-bold flex items-center gap-2">
          <i class="fa-solid fa-sparkles"></i>
          محتوای من
        </h1>
        <button class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm hover:bg-white/20 transition-all">
          <i class="fa-solid fa-sliders text-white"></i>
        </button>
      </div>
      <p class="text-white/80 text-sm leading-relaxed">
        محتواهای مرتبط با علایق شما
      </p>
    </div>

    <!-- Interest Tags -->
    <div class="px-5 py-4 bg-white border-b border-gray-100 sticky top-[136px] z-30">
      <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <span class="text-slate-600 text-xs flex-shrink-0">علایق شما:</span>
        <?php foreach ($userInterests as $interest): ?>
        <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 px-3 py-1.5 rounded-xl text-xs font-medium flex-shrink-0 border border-purple-200">
          <i class="fa-solid fa-tag text-[10px]"></i>
          <?= $interest ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-8 space-y-8">

      <!-- اخبار مرتبط -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            اخبار برای شما
          </h2>
        </div>
        <div class="space-y-4">
          <?php foreach ($personalizedNews as $news): ?>
          <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-gray-300 transition-all cursor-pointer">
            <img src="<?= $news['image'] ?>" alt="<?= $news['title'] ?>" class="w-full h-40 object-cover">
            <div class="p-4">
              <div class="flex items-center gap-2 mb-2">
                <?php foreach ($news['tags'] as $tag): ?>
                <span class="bg-gray-50 text-slate-600 px-2 py-0.5 rounded-lg text-xs font-medium">
                  #<?= $tag ?>
                </span>
                <?php endforeach; ?>
              </div>
              <h3 class="text-slate-900 text-sm font-bold mb-2 leading-tight"><?= $news['title'] ?></h3>
              <p class="text-slate-600 text-xs leading-relaxed mb-3"><?= $news['excerpt'] ?></p>
              <div class="flex items-center justify-between text-xs text-slate-400">
                <span><?= $news['date'] ?></span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-clock text-[10px]"></i>
                  <?= $news['readTime'] ?>
                </span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- تجربیات مرتبط -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            تجربیات مرتبط
          </h2>
        </div>
        <div class="space-y-3">
          <?php foreach ($personalizedExperiences as $exp): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all cursor-pointer">
            <h3 class="text-slate-900 text-sm font-semibold mb-2 leading-tight"><?= $exp['title'] ?></h3>
            <div class="flex items-center gap-2 mb-3">
              <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-user text-slate-400 text-xs"></i>
                <span class="text-slate-600 text-xs"><?= $exp['author'] ?></span>
              </div>
              <span class="text-slate-300">•</span>
              <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-building text-slate-400 text-xs"></i>
                <span class="text-slate-600 text-xs"><?= $exp['department'] ?></span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                  <i class="fa-solid fa-heart text-slate-400 text-xs"></i>
                  <span class="text-slate-600 text-xs"><?= $exp['likes'] ?></span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fa-solid fa-comment text-slate-400 text-xs"></i>
                  <span class="text-slate-600 text-xs"><?= $exp['comments'] ?></span>
                </div>
              </div>
              <span class="text-slate-400 text-xs"><?= $exp['date'] ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- آموزش‌های پیشنهادی -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            آموزش‌های پیشنهادی
          </h2>
        </div>
        <div class="space-y-3">
          <?php foreach ($recommendedTrainings as $training): ?>
          <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-gray-300 transition-all cursor-pointer">
            <img src="<?= $training['thumbnail'] ?>" alt="<?= $training['title'] ?>" class="w-full h-32 object-cover">
            <div class="p-4">
              <h3 class="text-slate-900 text-sm font-bold mb-2 leading-tight"><?= $training['title'] ?></h3>
              <div class="flex items-center gap-3 mb-3 text-xs text-slate-600">
                <div class="flex items-center gap-1">
                  <i class="fa-solid fa-clock text-slate-400"></i>
                  <?= $training['duration'] ?>
                </div>
                <span class="text-slate-300">•</span>
                <div class="flex items-center gap-1">
                  <i class="fa-solid fa-signal text-slate-400"></i>
                  <?= $training['level'] ?>
                </div>
              </div>
              <?php if ($training['progress'] > 0): ?>
              <div class="mb-3">
                <div class="flex items-center justify-between text-xs mb-1">
                  <span class="text-slate-600">پیشرفت</span>
                  <span class="text-slate-900 font-medium"><?= $training['progress'] ?>٪</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-slate-900 rounded-full transition-all" style="width: <?= $training['progress'] ?>%"></div>
                </div>
              </div>
              <?php endif; ?>
              <button class="w-full bg-slate-900 text-white py-2.5 rounded-xl text-xs font-medium hover:bg-slate-800 transition-all">
                <?= $training['progress'] > 0 ? 'ادامه آموزش' : 'شروع آموزش' ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- اطلاعیه‌های مرتبط -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-slate-900 text-base font-bold">
            رویدادهای پیشنهادی
          </h2>
        </div>
        <div class="space-y-3">
          <?php foreach ($personalizedAnnouncements as $announcement): ?>
          <div class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-all">
            <div class="flex items-start gap-3">
              <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-<?= $announcement['type'] === 'webinar' ? 'video' : 'chalkboard-teacher' ?> text-slate-600 text-lg"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-slate-900 text-sm font-semibold mb-2 leading-tight"><?= $announcement['title'] ?></h3>
                <div class="flex items-center gap-2 mb-3 text-xs text-slate-600">
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-calendar text-slate-400"></i>
                    <?= $announcement['date'] ?>
                  </div>
                  <span class="text-slate-300">•</span>
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-clock text-slate-400"></i>
                    <?= $announcement['time'] ?>
                  </div>
                </div>
                <?php if ($announcement['isRegistered']): ?>
                <div class="flex items-center gap-2 text-slate-600 text-xs font-medium">
                  <i class="fa-solid fa-check-circle"></i>
                  ثبت‌نام شده
                </div>
                <?php else: ?>
                <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-xs font-medium hover:bg-slate-800 transition-all">
                  ثبت‌نام در رویداد
                </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- Bottom Navigation -->
    <?php component('app-bottom-nav', ['currentTab' => $currentTab]); ?>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script>
    // Hide scrollbar for horizontal scroll
    const style = document.createElement('style');
    style.textContent = `
      .scrollbar-hide::-webkit-scrollbar {
        display: none;
      }
      .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
    `;
    document.head.appendChild(style);
  </script>

</body>
</html>
