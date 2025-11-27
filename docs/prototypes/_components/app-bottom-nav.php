<?php
/**
 * کامپوننت نوار پایین اپلیکیشن
 * Bottom Navigation با 4 تب اصلی
 */

// دریافت تب فعال
$currentTab = $currentTab ?? 'home';
?>

<!-- Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-2 py-3 z-50 max-w-[480px] mx-auto">
  <div class="flex items-center justify-around">
    <!-- خانه -->
    <a href="/app/index.php" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl <?= $currentTab === 'home' ? 'bg-slate-900 text-white' : 'hover:bg-gray-100 text-slate-600' ?> transition-all min-w-[68px]">
      <i class="fa-solid fa-home text-lg"></i>
      <span class="text-xs font-medium">خانه</span>
    </a>

    <!-- محتوای من -->
    <a href="/app/personalized.php" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl <?= $currentTab === 'personalized' ? 'bg-slate-900 text-white' : 'hover:bg-gray-100 text-slate-600' ?> transition-all min-w-[68px]">
      <i class="fa-solid fa-star text-lg"></i>
      <span class="text-xs font-medium">محتوای من</span>
    </a>

    <!-- آمار -->
    <a href="/app/analytics.php" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl <?= $currentTab === 'analytics' ? 'bg-slate-900 text-white' : 'hover:bg-gray-100 text-slate-600' ?> transition-all min-w-[68px]">
      <i class="fa-solid fa-chart-line text-lg"></i>
      <span class="text-xs font-medium">آمار</span>
    </a>

    <!-- پروفایل -->
    <a href="/app/profile.php" class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl <?= $currentTab === 'profile' ? 'bg-slate-900 text-white' : 'hover:bg-gray-100 text-slate-600' ?> transition-all min-w-[68px]">
      <i class="fa-solid fa-user text-lg"></i>
      <span class="text-xs font-medium">پروفایل</span>
    </a>
  </div>
</nav>
