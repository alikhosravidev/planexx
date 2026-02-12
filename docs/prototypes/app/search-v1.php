<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'جستجوی تجربیات';

// داده‌های نمونه تجربیات (در پروژه واقعی از API یا دیتابیس)
$allExperiences = [
    [
        'id' => 1,
        'title' => 'بهینه‌سازی فرآیند قراردادهای مالی',
        'summary' => 'با استفاده از اتوماسیون در فرآیند قراردادها، زمان پردازش را ۴۰٪ کاهش دادیم',
        'description' => 'این تجربه شامل پیاده‌سازی سیستم اتوماسیون برای مدیریت قراردادهای مالی است که منجر به کاهش چشمگیر زمان پردازش و افزایش دقت در ثبت اطلاعات شد.',
        'department' => 'مالی',
        'department_color' => 'green',
        'template' => 'تجربه قراردادی',
        'author' => 'احمد باقری',
        'author_position' => 'مدیر مالی',
        'date' => '۱۴۰۳/۰۹/۰۵',
        'views' => 145,
        'rating' => 4.8,
        'attachments' => 5,
        'tags' => ['اتوماسیون', 'قرارداد', 'مالی', 'بهینه‌سازی'],
        'keywords' => 'بهینه سازی فرآیند قرارداد مالی اتوماسیون کاهش زمان'
    ],
    [
        'id' => 2,
        'title' => 'کاهش زمان تولید محصول X از ۴ ساعت به ۲.۵ ساعت',
        'summary' => 'با تغییر چیدمان خط تولید و بهینه‌سازی مراحل، بهره‌وری ۳۷٪ افزایش یافت',
        'description' => 'تجدید ساختار خط تولید و حذف مراحل غیرضروری باعث افزایش قابل توجه بهره‌وری و کاهش هزینه‌های تولید شد.',
        'department' => 'تولید',
        'department_color' => 'slate',
        'template' => 'بهبود فرآیند تولید',
        'author' => 'رضا صانعی',
        'author_position' => 'مدیر تولید',
        'date' => '۱۴۰۳/۰۹/۰۴',
        'views' => 203,
        'rating' => 4.9,
        'attachments' => 3,
        'tags' => ['تولید', 'بهره‌وری', 'خط تولید', 'بهینه‌سازی'],
        'keywords' => 'کاهش زمان تولید محصول بهره وری خط تولید بهینه سازی'
    ],
    [
        'id' => 3,
        'title' => 'راهکار جدید جذب نیروی متخصص در حوزه IT',
        'summary' => 'با همکاری با دانشگاه‌ها و برگزاری رویدادهای تخصصی، استخدام را ۵۰٪ سریع‌تر کردیم',
        'description' => 'ایجاد شراکت با دانشگاه‌های برتر و برگزاری هکاتون‌های تخصصی، فرآیند جذب نیروی متخصص را تسریع و کیفیت استخدام را بهبود بخشید.',
        'department' => 'منابع انسانی',
        'department_color' => 'pink',
        'template' => 'استخدام و جذب نیرو',
        'author' => 'مریم نوری',
        'author_position' => 'مدیر منابع انسانی',
        'date' => '۱۴۰۳/۰۹/۰۳',
        'views' => 67,
        'rating' => 4.5,
        'attachments' => 2,
        'tags' => ['استخدام', 'IT', 'منابع انسانی', 'دانشگاه'],
        'keywords' => 'جذب نیرو متخصص IT استخدام منابع انسانی دانشگاه'
    ],
    [
        'id' => 4,
        'title' => 'افزایش ۶۰٪ فروش محصولات دیجیتال با تغییر استراتژی بازاریابی',
        'summary' => 'تمرکز روی شبکه‌های اجتماعی و اینفلوئنسر مارکتینگ نتایج فوق‌العاده‌ای داشت',
        'description' => 'بازنگری در استراتژی بازاریابی و تمرکز بر دیجیتال مارکتینگ منجر به افزایش چشمگیر فروش و شناخت برند شد.',
        'department' => 'فروش',
        'department_color' => 'orange',
        'template' => 'استراتژی فروش',
        'author' => 'فاطمه محمدی',
        'author_position' => 'مدیر فروش',
        'date' => '۱۴۰۳/۰۹/۰۲',
        'views' => 312,
        'rating' => 4.7,
        'attachments' => 8,
        'tags' => ['فروش', 'دیجیتال مارکتینگ', 'شبکه‌های اجتماعی', 'اینفلوئنسر'],
        'keywords' => 'افزایش فروش محصولات دیجیتال استراتژی بازاریابی شبکه اجتماعی'
    ],
    [
        'id' => 5,
        'title' => 'پیاده‌سازی CI/CD و کاهش زمان Deploy از ۲ ساعت به ۱۰ دقیقه',
        'summary' => 'با استفاده از Jenkins و Docker، فرآیند deployment را کاملاً خودکار کردیم',
        'description' => 'استقرار پایپ‌لاین CI/CD با استفاده از ابزارهای مدرن، کیفیت کد را افزایش و زمان انتشار نسخه‌های جدید را به شدت کاهش داد.',
        'department' => 'فنی',
        'department_color' => 'blue',
        'template' => 'توسعه نرم‌افزار',
        'author' => 'سارا قاسمی',
        'author_position' => 'مدیر فنی',
        'date' => '۱۴۰۳/۰۹/۰۱',
        'views' => 278,
        'rating' => 5.0,
        'attachments' => 12,
        'tags' => ['DevOps', 'CI/CD', 'Jenkins', 'Docker', 'اتوماسیون'],
        'keywords' => 'CI CD پیاده سازی کاهش زمان Deploy Jenkins Docker اتوماسیون'
    ],
    [
        'id' => 6,
        'title' => 'بهبود فرآیند onboarding کارکنان جدید',
        'summary' => 'با ایجاد چک‌لیست دیجیتال و منتورینگ، رضایت کارکنان جدید ۴۵٪ افزایش یافت',
        'description' => 'طراحی فرآیند جامع onboarding با استفاده از ابزارهای دیجیتال و برنامه منتورینگ، تجربه کارکنان جدید را بهبود بخشید.',
        'department' => 'منابع انسانی',
        'department_color' => 'pink',
        'template' => 'استخدام و جذب نیرو',
        'author' => 'مریم نوری',
        'author_position' => 'مدیر منابع انسانی',
        'date' => '۱۴۰۳/۰۸/۲۸',
        'views' => 189,
        'rating' => 4.6,
        'attachments' => 4,
        'tags' => ['onboarding', 'منابع انسانی', 'منتورینگ', 'رضایت شغلی'],
        'keywords' => 'بهبود فرآیند onboarding کارکنان جدید منتورینگ رضایت'
    ],
    [
        'id' => 7,
        'title' => 'کاهش ضایعات تولید با استفاده از IoT',
        'summary' => 'نصب سنسورها و مانیتورینگ لحظه‌ای باعث کاهش ۲۵٪ ضایعات شد',
        'description' => 'پیاده‌سازی سیستم IoT برای نظارت بر فرآیند تولید، شناسایی به موقع مشکلات و کاهش ضایعات را ممکن ساخت.',
        'department' => 'تولید',
        'department_color' => 'slate',
        'template' => 'بهبود فرآیند تولید',
        'author' => 'رضا صانعی',
        'author_position' => 'مدیر تولید',
        'date' => '۱۴۰۳/۰۸/۲۵',
        'views' => 156,
        'rating' => 4.4,
        'attachments' => 6,
        'tags' => ['IoT', 'تولید', 'ضایعات', 'مانیتورینگ'],
        'keywords' => 'کاهش ضایعات تولید IoT سنسور مانیتورینگ'
    ],
    [
        'id' => 8,
        'title' => 'مدیریت بحران کرونا در سازمان',
        'summary' => 'استراتژی دورکاری و ابزارهای همکاری آنلاین را با موفقیت پیاده‌سازی کردیم',
        'description' => 'طراحی و اجرای برنامه جامع دورکاری و استفاده از ابزارهای همکاری آنلاین، تداوم عملیات سازمان را در دوران بحران تضمین کرد.',
        'department' => 'مدیریت',
        'department_color' => 'purple',
        'template' => 'مدیریت پروژه',
        'author' => 'محمد رضایی',
        'author_position' => 'مدیرعامل',
        'date' => '۱۴۰۳/۰۸/۲۰',
        'views' => 423,
        'rating' => 4.9,
        'attachments' => 15,
        'tags' => ['دورکاری', 'بحران', 'مدیریت پروژه', 'همکاری آنلاین'],
        'keywords' => 'مدیریت بحران کرونا دورکاری ابزار همکاری آنلاین'
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-gray-50">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative pb-24">

    <!-- Header با پس‌زمینه رنگی -->
    <div class="bg-gradient-to-br from-teal-600 to-teal-800 px-5 pt-8 pb-12 rounded-b-[32px] shadow-lg">
      <div class="text-center mb-6">
        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
          <i class="fa-solid fa-magnifying-glass text-white text-3xl"></i>
        </div>
        <h1 class="text-white text-2xl font-bold leading-tight mb-2">
          جستجوی تجربیات
        </h1>
        <p class="text-white/80 text-sm leading-relaxed">
          تجربیات ارزشمند سازمان را پیدا کنید
        </p>
      </div>
      
      <!-- Search Box -->
      <div class="bg-white rounded-2xl p-3 shadow-xl">
        <div class="flex items-center gap-2">
          
          <!-- Input جستجو -->
          <div class="flex-1 flex items-center">
            <i class="fa-solid fa-search text-text-muted text-base mr-3"></i>
            <input 
              type="text" 
              id="searchInput"
              class="flex-1 py-3 ps-2 text-base text-text-primary outline-none bg-transparent leading-normal placeholder:text-text-muted"
              placeholder="جستجو کنید..."
              autocomplete="off">
          </div>
          
          <!-- دکمه میکروفون (Voice Input) -->
          <button 
            id="voiceBtn"
            class="w-11 h-11 flex items-center justify-center text-text-muted hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all duration-200"
            title="جستجوی صوتی">
            <i class="fa-solid fa-microphone text-lg"></i>
          </button>
          
        </div>
      </div>
    </div>
    
    <!-- نتایج جستجو -->
    <div id="resultsContainer" class="px-4 -mt-4">
      
      <!-- پیام خالی (نمایش اولیه) -->
      <div id="emptyState" class="text-center py-16">
        <div class="w-20 h-20 mx-auto mb-5 flex items-center justify-center bg-gray-100 rounded-full">
          <i class="fa-solid fa-magnifying-glass text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-text-primary mb-2 leading-snug">
          برای شروع جستجو کنید
        </h3>
        <p class="text-sm text-text-secondary leading-normal">
          عبارت مورد نظر خود را وارد کنید
        </p>
      </div>
      
      <!-- لیست نتایج -->
      <div id="resultsList" class="space-y-3 pb-6 hidden">
        <!-- نتایج به صورت داینامیک اینجا اضافه می‌شوند -->
      </div>
      
      <!-- پیام "نتیجه‌ای یافت نشد" -->
      <div id="noResults" class="text-center py-16 hidden">
        <div class="w-20 h-20 mx-auto mb-5 flex items-center justify-center bg-red-50 rounded-full">
          <i class="fa-solid fa-search-minus text-3xl text-red-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-text-primary mb-2 leading-snug">
          نتیجه‌ای یافت نشد
        </h3>
        <p class="text-sm text-text-secondary leading-normal">
          لطفاً کلمات دیگری را امتحان کنید
        </p>
      </div>
      
    </div>
    
    <!-- Bottom Navigation -->
    <?php component('app-bottom-nav', ['currentTab' => 'search']); ?>
    
  </div>
  
  <!-- Modal جزئیات تجربه -->
  <div id="experienceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-[460px] w-full max-h-[85vh] overflow-y-auto">
      
      <!-- Header Modal -->
      <div class="sticky top-0 bg-white px-5 py-5 border-b border-gray-200 flex items-start justify-between gap-3 z-10">
        <div class="flex-1">
          <h2 id="modalTitle" class="text-lg font-bold text-text-primary leading-snug mb-2"></h2>
          <div id="modalMeta" class="flex flex-wrap items-center gap-2 text-xs text-text-secondary"></div>
        </div>
        <button 
          id="closeModal"
          class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-primary hover:bg-gray-100 rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times text-lg"></i>
        </button>
      </div>
      
      <!-- Body Modal -->
      <div class="p-5">
        
        <!-- Summary -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug">خلاصه</h3>
          <p id="modalSummary" class="text-sm text-text-primary leading-relaxed"></p>
        </div>
        
        <!-- Description -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug">توضیحات کامل</h3>
          <p id="modalDescription" class="text-sm text-text-primary leading-relaxed"></p>
        </div>
        
        <!-- Tags -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug">برچسب‌ها</h3>
          <div id="modalTags" class="flex flex-wrap gap-2"></div>
        </div>
        
        <!-- Badges -->
        <div id="modalBadges" class="flex flex-wrap gap-2 mb-5"></div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-gray-50 rounded-xl p-3 text-center">
            <div class="text-xl font-bold text-primary mb-0.5" id="modalViews"></div>
            <div class="text-xs text-text-secondary">بازدید</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3 text-center">
            <div class="text-xl font-bold text-primary mb-0.5" id="modalRating"></div>
            <div class="text-xs text-text-secondary">امتیاز</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3 text-center">
            <div class="text-xl font-bold text-primary mb-0.5" id="modalAttachments"></div>
            <div class="text-xs text-text-secondary">پیوست</div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3 text-center">
            <div class="text-xl font-bold text-primary mb-0.5" id="modalDate"></div>
            <div class="text-xs text-text-secondary">تاریخ</div>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  // داده‌های تجربیات (از PHP به JavaScript)
  const experiences = <?= json_encode($allExperiences, JSON_UNESCAPED_UNICODE) ?>;
  
  // عناصر DOM
  const searchInput = document.getElementById('searchInput');
  const voiceBtn = document.getElementById('voiceBtn');
  const resultsContainer = document.getElementById('resultsContainer');
  const emptyState = document.getElementById('emptyState');
  const resultsList = document.getElementById('resultsList');
  const noResults = document.getElementById('noResults');
  const experienceModal = document.getElementById('experienceModal');
  const closeModal = document.getElementById('closeModal');
  
  // تابع تبدیل اعداد انگلیسی به فارسی
  function toPersianNumber(num) {
    const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return String(num).replace(/\d/g, digit => persianDigits[digit]);
  }
  
  // تابع جستجو
  function performSearch() {
    const query = searchInput.value.trim().toLowerCase();
    
    if (query === '') {
      // اگر جستجو خالی بود، حالت اولیه را نمایش بده
      emptyState.classList.remove('hidden');
      resultsList.classList.add('hidden');
      noResults.classList.add('hidden');
      return;
    }
    
    // فیلتر تجربیات بر اساس query
    const filteredExperiences = experiences.filter(exp => {
      const searchableText = `${exp.title} ${exp.summary} ${exp.description} ${exp.department} ${exp.author} ${exp.keywords} ${exp.tags.join(' ')}`.toLowerCase();
      return searchableText.includes(query);
    });
    
    // مخفی کردن empty state
    emptyState.classList.add('hidden');
    
    if (filteredExperiences.length === 0) {
      // نتیجه‌ای یافت نشد
      resultsList.classList.add('hidden');
      noResults.classList.remove('hidden');
    } else {
      // نمایش نتایج
      noResults.classList.add('hidden');
      resultsList.classList.remove('hidden');
      renderResults(filteredExperiences, query);
    }
  }
  
  // تابع رندر نتایج
  function renderResults(results, query) {
    resultsList.innerHTML = '';
    
    results.forEach(exp => {
      const card = document.createElement('div');
      card.className = 'bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-teal-300 transition-all duration-200 cursor-pointer';
      card.onclick = () => showExperienceDetails(exp);
      
      // Highlight کلمات جستجو شده
      const highlightedTitle = highlightText(exp.title, query);
      const highlightedSummary = highlightText(exp.summary, query);
      
      card.innerHTML = `
        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-2">
          <h3 class="flex-1 text-base font-semibold text-text-primary leading-snug">${highlightedTitle}</h3>
          <div class="flex items-center gap-1 text-xs text-teal-600 font-medium whitespace-nowrap">
            <i class="fa-solid fa-star text-[10px]"></i>
            <span>${toPersianNumber(exp.rating)}</span>
          </div>
        </div>
        
        <!-- Summary -->
        <p class="text-sm text-text-secondary leading-relaxed mb-3">${highlightedSummary}</p>
        
        <!-- Badges -->
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="inline-flex items-center gap-1.5 bg-${exp.department_color}-50 text-${exp.department_color}-700 px-2 py-0.5 rounded-md text-xs font-medium leading-normal">
            <i class="fa-solid fa-sitemap text-[9px]"></i>
            ${exp.department}
          </span>
          <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md text-xs font-medium leading-normal">
            <i class="fa-solid fa-file-lines text-[9px]"></i>
            ${exp.template}
          </span>
        </div>
        
        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-3 text-xs text-text-muted leading-normal">
          <span class="inline-flex items-center gap-1.5">
            <i class="fa-solid fa-user text-[10px]"></i>
            ${exp.author}
          </span>
          <span class="inline-flex items-center gap-1.5">
            <i class="fa-solid fa-eye text-[10px]"></i>
            ${toPersianNumber(exp.views)}
          </span>
          <span class="inline-flex items-center gap-1.5">
            <i class="fa-solid fa-paperclip text-[10px]"></i>
            ${toPersianNumber(exp.attachments)}
          </span>
        </div>
      `;
      
      resultsList.appendChild(card);
    });
  }
  
  // تابع Highlight متن
  function highlightText(text, query) {
    if (!query) return text;
    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark class="bg-yellow-200 text-text-primary px-1 rounded">$1</mark>');
  }
  
  // تابع نمایش جزئیات تجربه در Modal
  function showExperienceDetails(exp) {
    // پر کردن محتوای Modal
    document.getElementById('modalTitle').textContent = exp.title;
    document.getElementById('modalSummary').textContent = exp.summary;
    document.getElementById('modalDescription').textContent = exp.description;
    document.getElementById('modalViews').textContent = toPersianNumber(exp.views);
    document.getElementById('modalRating').textContent = toPersianNumber(exp.rating);
    document.getElementById('modalAttachments').textContent = toPersianNumber(exp.attachments);
    document.getElementById('modalDate').textContent = exp.date;
    
    // Meta
    const modalMeta = document.getElementById('modalMeta');
    modalMeta.innerHTML = `
      <span class="inline-flex items-center gap-1.5">
        <i class="fa-solid fa-user text-xs"></i>
        ${exp.author} - ${exp.author_position}
      </span>
      <span class="inline-flex items-center gap-1.5">
        <i class="fa-solid fa-calendar text-xs"></i>
        ${exp.date}
      </span>
    `;
    
    // Tags
    const modalTags = document.getElementById('modalTags');
    modalTags.innerHTML = exp.tags.map(tag => 
      `<span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-medium leading-normal">
        <i class="fa-solid fa-tag text-[10px]"></i>
        ${tag}
      </span>`
    ).join('');
    
    // Badges
    const modalBadges = document.getElementById('modalBadges');
    modalBadges.innerHTML = `
      <span class="inline-flex items-center gap-1.5 bg-${exp.department_color}-50 text-${exp.department_color}-700 px-3 py-1.5 rounded-lg text-sm font-medium leading-normal">
        <i class="fa-solid fa-sitemap text-xs"></i>
        ${exp.department}
      </span>
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium leading-normal">
        <i class="fa-solid fa-file-lines text-xs"></i>
        ${exp.template}
      </span>
    `;
    
    // نمایش Modal
    experienceModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  
  // بستن Modal
  function hideModal() {
    experienceModal.classList.add('hidden');
    document.body.style.overflow = '';
  }
  
  closeModal.addEventListener('click', hideModal);
  experienceModal.addEventListener('click', (e) => {
    if (e.target === experienceModal) {
      hideModal();
    }
  });
  
  // رویداد Enter در input
  searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      performSearch();
    }
  });
  
  // Voice Input (Web Speech API)
  if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'fa-IR';
    recognition.continuous = false;
    recognition.interimResults = false;
    
    voiceBtn.addEventListener('click', () => {
      recognition.start();
      voiceBtn.classList.add('text-red-600', 'animate-pulse');
      voiceBtn.querySelector('i').classList.remove('fa-microphone');
      voiceBtn.querySelector('i').classList.add('fa-microphone-slash');
    });
    
    recognition.onresult = (event) => {
      const transcript = event.results[0][0].transcript;
      searchInput.value = transcript;
      performSearch();
      voiceBtn.classList.remove('text-red-600', 'animate-pulse');
      voiceBtn.querySelector('i').classList.remove('fa-microphone-slash');
      voiceBtn.querySelector('i').classList.add('fa-microphone');
    };
    
    recognition.onerror = () => {
      voiceBtn.classList.remove('text-red-600', 'animate-pulse');
      voiceBtn.querySelector('i').classList.remove('fa-microphone-slash');
      voiceBtn.querySelector('i').classList.add('fa-microphone');
    };
    
    recognition.onend = () => {
      voiceBtn.classList.remove('text-red-600', 'animate-pulse');
      voiceBtn.querySelector('i').classList.remove('fa-microphone-slash');
      voiceBtn.querySelector('i').classList.add('fa-microphone');
    };
  } else {
    // مرورگر از Voice Input پشتیبانی نمی‌کند
    voiceBtn.disabled = true;
    voiceBtn.classList.add('opacity-50', 'cursor-not-allowed');
    voiceBtn.title = 'مرورگر شما از جستجوی صوتی پشتیبانی نمی‌کند';
  }
  
  // جستجوی زنده (Live Search) - اختیاری
  let searchTimeout;
  searchInput.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      if (searchInput.value.trim().length >= 2) {
        performSearch();
      }
    }, 500);
  });
  </script>
  
</body>
</html>
