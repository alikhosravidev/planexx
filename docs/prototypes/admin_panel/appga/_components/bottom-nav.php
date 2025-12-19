<?php
/**
 * کامپوننت نوار پایین اپلیکیشن کارکنان
 * Bottom Navigation با 4 تب اصلی
 */

// دریافت تب فعال
$currentTab = $currentTab ?? 'home';
?>

<!-- Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 bg-white px-4 py-2 z-50 max-w-[480px] mx-auto shadow-[0_-8px_24px_rgba(0,0,0,0.08)]">
  <div class="flex items-center justify-around">
    
    <!-- خانه -->
    <a href="index.php" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl <?= $currentTab === 'home' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-gray-100' ?> transition-all min-w-[64px]">
      <i class="fa-solid fa-home text-lg"></i>
      <span class="text-xs font-medium">خانه</span>
    </a>

    <!-- هیستوری -->
    <a href="history.php" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl <?= $currentTab === 'history' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-gray-100' ?> transition-all min-w-[64px]">
      <i class="fa-solid fa-clock-rotate-left text-lg"></i>
      <span class="text-xs font-medium">تاریخچه</span>
    </a>

    <!-- گزارش‌ها -->
    <a href="reports.php" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl <?= $currentTab === 'reports' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-gray-100' ?> transition-all min-w-[64px]">
      <i class="fa-solid fa-chart-pie text-lg"></i>
      <span class="text-xs font-medium">گزارش‌ها</span>
    </a>

    <!-- پروفایل -->
    <a href="profile.php" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl <?= $currentTab === 'profile' ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-gray-100' ?> transition-all min-w-[64px]">
      <i class="fa-solid fa-user text-lg"></i>
      <span class="text-xs font-medium">پروفایل</span>
    </a>

  </div>
</nav>
