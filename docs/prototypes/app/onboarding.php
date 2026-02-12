<?php
/**
 * صفحه آنبوردینگ - جمع‌آوری اطلاعات و علایق کاربر
 * این صفحه فقط یکبار در اولین ورود نمایش داده می‌شود
 */

// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'خوش آمدید';

// دسته‌بندی‌های علایق
$interestCategories = [
    [
        'title' => 'مدیریت و رهبری',
        'tags' => ['مدیریت پروژه', 'رهبری تیم', 'استراتژی', 'برنامه‌ریزی', 'تصمیم‌گیری']
    ],
    [
        'title' => 'فروش و بازاریابی',
        'tags' => ['فروش', 'بازاریابی دیجیتال', 'مشتری‌مداری', 'CRM', 'برندینگ']
    ],
    [
        'title' => 'تولید و عملیات',
        'tags' => ['تولید', 'کنترل کیفیت', 'بهینه‌سازی', 'زنجیره تامین', 'لجستیک']
    ],
    [
        'title' => 'مالی و حسابداری',
        'tags' => ['مدیریت مالی', 'حسابداری', 'بودجه‌بندی', 'گزارش‌گیری مالی', 'مالیات']
    ],
    [
        'title' => 'منابع انسانی',
        'tags' => ['استخدام', 'آموزش کارکنان', 'ارزیابی عملکرد', 'فرهنگ سازمانی', 'حقوق و دستمزد']
    ],
    [
        'title' => 'فناوری اطلاعات',
        'tags' => ['توسعه نرم‌افزار', 'امنیت سایبری', 'زیرساخت IT', 'تحول دیجیتال', 'داده‌کاوی']
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative">

    <!-- Progress Steps -->
    <div class="bg-white px-5 pt-8 pb-6 border-b border-gray-100 sticky top-0 z-40">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-slate-900 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
          <div class="w-12 h-1 bg-slate-900 rounded-full"></div>
          <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-sm font-bold">2</div>
          <div class="w-12 h-1 bg-gray-200 rounded-full"></div>
          <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-sm font-bold">3</div>
        </div>
        <button class="text-slate-600 text-sm font-medium">رد کردن</button>
      </div>
      <h1 class="text-slate-900 text-xl font-bold mb-1">به ساپل خوش آمدید! 🎉</h1>
      <p class="text-slate-600 text-sm">بیایید تجربه شما را شخصی‌سازی کنیم</p>
    </div>

    <!-- Step 1: Welcome -->
    <div id="step1" class="px-5 py-6">
      <div class="text-center mb-8">
        <div class="w-32 h-32 bg-gradient-to-br from-slate-900 to-slate-700 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
          <i class="fa-solid fa-rocket text-white text-5xl"></i>
        </div>
        <h2 class="text-slate-900 text-2xl font-bold mb-3">آماده شروع هستید؟</h2>
        <p class="text-slate-600 text-base leading-relaxed px-4">
          ما می‌خواهیم محتوایی که واقعاً برای شما مفید است را نمایش دهیم. 
          چند سوال ساده از شما می‌پرسیم.
        </p>
      </div>

      <div class="space-y-4 mb-8">
        <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4 flex items-start gap-3">
          <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-star text-sky-600 text-lg"></i>
          </div>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">محتوای شخصی‌سازی شده</h3>
            <p class="text-slate-600 text-xs leading-relaxed">اخبار و آموزش‌های مرتبط با شغل شما</p>
          </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
          <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-trophy text-amber-600 text-lg"></i>
          </div>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">کسب امتیاز و دستاورد</h3>
            <p class="text-slate-600 text-xs leading-relaxed">با فعالیت در سیستم امتیاز بگیرید</p>
          </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4 flex items-start gap-3">
          <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-users text-purple-600 text-lg"></i>
          </div>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">اشتراک تجربه</h3>
            <p class="text-slate-600 text-xs leading-relaxed">تجربیات خود را با همکاران به اشتراک بگذارید</p>
          </div>
        </div>
      </div>

      <button onclick="goToStep(2)" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-base hover:bg-slate-800 active:bg-slate-950 transition-all shadow-lg">
        بزن بریم! 🚀
      </button>
    </div>

    <!-- Step 2: Select Interests (Hidden by default) -->
    <div id="step2" class="hidden px-5 py-6">
      <div class="mb-6">
        <h2 class="text-slate-900 text-xl font-bold mb-2">علایق شما چیست؟</h2>
        <p class="text-slate-600 text-sm">حداقل ۳ مورد انتخاب کنید تا محتوای مرتبط را ببینید</p>
      </div>

      <div class="space-y-6 mb-6">
        <?php foreach ($interestCategories as $category): ?>
        <div>
          <h3 class="text-slate-900 text-sm font-bold mb-3 flex items-center gap-2">
            <i class="fa-solid fa-folder text-slate-400"></i>
            <?= $category['title'] ?>
          </h3>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($category['tags'] as $tag): ?>
            <button onclick="toggleTag(this)" class="tag-button px-4 py-2 rounded-xl border-2 border-gray-200 text-slate-700 text-sm font-medium hover:border-slate-300 transition-all active:scale-95">
              <?= $tag ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-5 z-50 max-w-[480px] mx-auto">
        <div class="flex gap-3">
          <button onclick="goToStep(1)" class="flex-1 bg-gray-100 text-slate-700 py-4 rounded-2xl font-bold hover:bg-gray-200 transition-all">
            قبلی
          </button>
          <button id="nextStepBtn" onclick="goToStep(3)" disabled class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <span id="selectedCount">انتخاب کنید (۰/۳)</span>
          </button>
        </div>
      </div>
      <div class="h-20"></div>
    </div>

    <!-- Step 3: Notification Preferences (Hidden by default) -->
    <div id="step3" class="hidden px-5 py-6">
      <div class="text-center mb-8">
        <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
          <i class="fa-solid fa-bell text-white text-4xl"></i>
        </div>
        <h2 class="text-slate-900 text-xl font-bold mb-2">اعلان‌ها را فعال کنید</h2>
        <p class="text-slate-600 text-sm leading-relaxed">
          برای اطلاع از اخبار، تجربیات جدید و وظایف خود اعلان‌ها را فعال کنید
        </p>
      </div>

      <div class="space-y-4 mb-8">
        <label class="bg-white border-2 border-gray-200 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-slate-300 transition-all active:scale-[0.98]">
          <input type="checkbox" class="mt-1 w-5 h-5 text-slate-900 rounded accent-slate-900" checked>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">اخبار و اطلاعیه‌ها</h3>
            <p class="text-slate-600 text-xs">دریافت آخرین اخبار سازمان</p>
          </div>
        </label>

        <label class="bg-white border-2 border-gray-200 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-slate-300 transition-all active:scale-[0.98]">
          <input type="checkbox" class="mt-1 w-5 h-5 text-slate-900 rounded accent-slate-900" checked>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">تجربیات جدید</h3>
            <p class="text-slate-600 text-xs">اطلاع از انتشار تجربیات مرتبط</p>
          </div>
        </label>

        <label class="bg-white border-2 border-gray-200 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-slate-300 transition-all active:scale-[0.98]">
          <input type="checkbox" class="mt-1 w-5 h-5 text-slate-900 rounded accent-slate-900" checked>
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">وظایف و تسک‌ها</h3>
            <p class="text-slate-600 text-xs">یادآوری وظایف و deadline‌ها</p>
          </div>
        </label>

        <label class="bg-white border-2 border-gray-200 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:border-slate-300 transition-all active:scale-[0.98]">
          <input type="checkbox" class="mt-1 w-5 h-5 text-slate-900 rounded accent-slate-900">
          <div>
            <h3 class="text-slate-900 text-sm font-bold mb-1">دستاوردها و امتیازات</h3>
            <p class="text-slate-600 text-xs">اطلاع از کسب امتیاز و دستاورد جدید</p>
          </div>
        </label>
      </div>

      <div class="space-y-3">
        <button onclick="completeOnboarding()" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-base hover:bg-slate-800 active:bg-slate-950 transition-all shadow-lg">
          شروع استفاده 🎊
        </button>
        <button onclick="skipNotifications()" class="w-full text-slate-600 py-3 rounded-2xl font-medium text-sm hover:text-slate-900 transition-all">
          فعلاً نه، بعداً فعال می‌کنم
        </button>
      </div>
    </div>

  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script>
    let selectedTags = new Set();

    function goToStep(stepNumber) {
      // مخفی کردن همه step‌ها
      document.getElementById('step1').classList.add('hidden');
      document.getElementById('step2').classList.add('hidden');
      document.getElementById('step3').classList.add('hidden');

      // نمایش step مورد نظر
      document.getElementById('step' + stepNumber).classList.remove('hidden');

      // به‌روزرسانی progress indicator
      updateProgressIndicator(stepNumber);

      // اسکرول به بالا
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateProgressIndicator(activeStep) {
      const steps = document.querySelectorAll('[class*="w-8 h-8"]');
      const lines = document.querySelectorAll('[class*="w-12 h-1"]');

      steps.forEach((step, index) => {
        const stepNum = index + 1;
        if (stepNum < activeStep) {
          step.className = 'w-8 h-8 bg-slate-900 rounded-full flex items-center justify-center text-white text-sm font-bold';
        } else if (stepNum === activeStep) {
          step.className = 'w-8 h-8 bg-slate-900 rounded-full flex items-center justify-center text-white text-sm font-bold';
        } else {
          step.className = 'w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-sm font-bold';
        }
      });

      lines.forEach((line, index) => {
        if (index < activeStep - 1) {
          line.className = 'w-12 h-1 bg-slate-900 rounded-full';
        } else {
          line.className = 'w-12 h-1 bg-gray-200 rounded-full';
        }
      });
    }

    function toggleTag(button) {
      const tagText = button.textContent.trim();

      if (selectedTags.has(tagText)) {
        selectedTags.delete(tagText);
        button.className = 'tag-button px-4 py-2 rounded-xl border-2 border-gray-200 text-slate-700 text-sm font-medium hover:border-slate-300 transition-all active:scale-95';
      } else {
        selectedTags.add(tagText);
        button.className = 'tag-button px-4 py-2 rounded-xl border-2 border-slate-900 bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition-all active:scale-95';
      }

      updateNextButton();
    }

    function updateNextButton() {
      const count = selectedTags.size;
      const nextBtn = document.getElementById('nextStepBtn');
      const countText = document.getElementById('selectedCount');

      if (count >= 3) {
        nextBtn.disabled = false;
        countText.textContent = 'ادامه (' + count + ' انتخاب شد)';
      } else {
        nextBtn.disabled = true;
        countText.textContent = 'انتخاب کنید (' + count + '/۳)';
      }
    }

    function completeOnboarding() {
      // ذخیره علایق کاربر در localStorage
      localStorage.setItem('userInterests', JSON.stringify([...selectedTags]));
      localStorage.setItem('onboardingCompleted', 'true');

      // انتقال به صفحه اصلی
      window.location.href = 'index.php';
    }

    function skipNotifications() {
      completeOnboarding();
    }
  </script>

</body>
</html>
