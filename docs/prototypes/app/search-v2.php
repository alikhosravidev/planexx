<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'جستجوی هوشمند تجربیات';

// داده‌های نمونه تجربیات (در پروژه واقعی از API یا دیتابیس)
$allExperiences = [
    [
        'id' => 1,
        'title' => 'بهینه‌سازی فرآیند قراردادهای مالی',
        'summary' => 'با استفاده از اتوماسیون در فرآیند قراردادها، زمان پردازش را ۴۰٪ کاهش دادیم',
        'description' => 'این تجربه شامل پیاده‌سازی سیستم اتوماسیون برای مدیریت قراردادهای مالی است که منجر به کاهش چشمگیر زمان پردازش و افزایش دقت در ثبت اطلاعات شد. تیم ما با تحلیل دقیق فرآیندها، نقاط بهبود را شناسایی و با استفاده از ابزارهای مدرن، یک سیستم یکپارچه طراحی کردیم.',
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
        'description' => 'تجدید ساختار خط تولید و حذف مراحل غیرضروری باعث افزایش قابل توجه بهره‌وری و کاهش هزینه‌های تولید شد. این پروژه شامل تحلیل کامل فرآیند تولید، شناسایی گلوگاه‌ها و طراحی مجدد چیدمان بهینه بود.',
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
        'description' => 'ایجاد شراکت با دانشگاه‌های برتر و برگزاری هکاتون‌های تخصصی، فرآیند جذب نیروی متخصص را تسریع و کیفیت استخدام را بهبود بخشید. این استراتژی شامل برگزاری رویدادهای ماهانه، ارائه چالش‌های واقعی و ایجاد ارتباط مستقیم با استعدادها بود.',
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
        'description' => 'بازنگری در استراتژی بازاریابی و تمرکز بر دیجیتال مارکتینگ منجر به افزایش چشمگیر فروش و شناخت برند شد. تیم بازاریابی با تحلیل دقیق مخاطبان و رقبا، استراتژی جامعی طراحی کرد که شامل تولید محتوای هدفمند، همکاری با اینفلوئنسرها و کمپین‌های تعاملی بود.',
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
        'description' => 'استقرار پایپ‌لاین CI/CD با استفاده از ابزارهای مدرن، کیفیت کد را افزایش و زمان انتشار نسخه‌های جدید را به شدت کاهش داد. این پروژه شامل طراحی معماری میکروسرویس، پیکربندی Jenkins برای اتوماسیون تست و deploy، و استفاده از Docker برای ایزوله‌سازی محیط‌ها بود.',
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
        'description' => 'طراحی فرآیند جامع onboarding با استفاده از ابزارهای دیجیتال و برنامه منتورینگ، تجربه کارکنان جدید را بهبود بخشید. این برنامه شامل ایجاد پورتال اختصاصی، تعیین منتور برای هر فرد، برگزاری جلسات معارفه و پیگیری مستمر پیشرفت بود.',
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
        'description' => 'پیاده‌سازی سیستم IoT برای نظارت بر فرآیند تولید، شناسایی به موقع مشکلات و کاهش ضایعات را ممکن ساخت. این پروژه شامل نصب سنسورهای هوشمند، طراحی داشبورد مانیتورینگ و ایجاد سیستم هشدار خودکار بود.',
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
        'description' => 'طراحی و اجرای برنامه جامع دورکاری و استفاده از ابزارهای همکاری آنلاین، تداوم عملیات سازمان را در دوران بحران تضمین کرد. این برنامه شامل انتخاب ابزارهای مناسب، آموزش کارکنان، تنظیم فرآیندهای جدید و پشتیبانی مستمر بود.',
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

<body class="bg-gradient-to-br from-slate-50 to-gray-100">

  <!-- App Container -->
  <div class="max-w-[480px] mx-auto bg-white min-h-screen shadow-xl relative flex flex-col">

    <!-- Header با طراحی مینیمال -->
    <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 px-5 pt-6 pb-8">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-sparkles text-white text-lg"></i>
          </div>
          <div>
            <h1 class="text-white text-lg font-bold leading-tight">جستجوی هوشمند</h1>
            <p class="text-white/70 text-xs">تجربیات سازمانی</p>
          </div>
        </div>
        <a href="/app/search-v1.php" class="w-9 h-9 bg-white/10 backdrop-blur-md rounded-lg flex items-center justify-center hover:bg-white/20 transition-all">
          <i class="fa-solid fa-arrow-right text-white text-sm"></i>
        </a>
      </div>
    </div>

    <!-- محتوای اصلی (قابل scroll) -->
    <div class="flex-1 overflow-y-auto px-4 pt-4 pb-52" id="chatContainer">
      
      <!-- پیام خوش‌آمدگویی -->
      <div id="welcomeMessage" class="text-center py-12">
        <div class="w-20 h-20 mx-auto mb-5 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-xl">
          <i class="fa-solid fa-wand-magic-sparkles text-3xl text-white"></i>
        </div>
        <h2 class="text-xl font-bold text-text-primary mb-2 leading-snug">
          چه چیزی می‌خواهید بیابید؟
        </h2>
        <p class="text-sm text-text-secondary leading-relaxed max-w-xs mx-auto">
          سوال خود را بپرسید تا تجربیات مرتبط را برایتان پیدا کنیم
        </p>
      </div>
      
      <!-- نتایج جستجو (به صورت داینامیک) -->
      <div id="searchResults" class="hidden space-y-4"></div>
      
    </div>

    <!-- Input Container (ثابت در پایین - Sticky) -->
    <div class="fixed bottom-0 left-0 right-0 max-w-[480px] mx-auto bg-gradient-to-t from-white via-white to-transparent pt-4 pb-4 px-4 z-40">
      
      <!-- Input Box -->
      <div class="bg-white border-2 border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 focus-within:border-indigo-500">
        <div class="flex flex-col gap-2 p-4">
          
          <!-- Textarea -->
          <div class="flex-1">
            <textarea 
              id="queryInput"
              rows="4"
              class="w-full text-md text-text-primary outline-none resize-none leading-relaxed placeholder:text-text-muted"
              placeholder="سوال خود را بپرسید یا موضوع مورد نظر را جستجو کنید..."
              style="min-height: 60px; max-height: 200px; overflow-y: auto;"></textarea>
          </div>
          
          <!-- دکمه‌های عملیاتی در پایین چپ -->
<div class="flex items-center justify-between">
            <div class="flex items-center gap-1">
              
              <!-- دکمه الصاق فایل -->
              <button id="attachBtn" class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="الصاق فایل">
                <i class="fa-solid fa-paperclip text-lg"></i>
              </button>
              
              <!-- دکمه میکروفون -->
              <button id="voiceBtn" class="w-9 h-9 flex items-center justify-center text-text-muted hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="جستجوی صوتی">
                <i class="fa-solid fa-microphone text-lg"></i>
              </button>
              
            </div>
            
            <!-- دکمه ارسال -->
            <button id="sendBtn" class="w-9 h-9 flex items-center justify-center bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" title="جستجو">
              <i class="fa-solid fa-arrow-up text-lg"></i>
            </button>
            
          </div>        </div>
      </div>
      
    </div>

  </div>

  <!-- Modal جزئیات تجربه -->
  <div id="experienceModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-[460px] w-full max-h-[85vh] overflow-y-auto animate-slideUp">
      
      <!-- Header Modal -->
      <div class="sticky top-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 px-5 py-5 flex items-start justify-between gap-3 z-10">
        <div class="flex-1">
          <h2 id="modalTitle" class="text-lg font-bold text-white leading-snug mb-2"></h2>
          <div id="modalMeta" class="flex flex-wrap items-center gap-2 text-xs text-white/80"></div>
        </div>
        <button 
          id="closeModal"
          class="w-9 h-9 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all duration-200">
          <i class="fa-solid fa-times text-lg"></i>
        </button>
      </div>
      
      <!-- Body Modal -->
      <div class="p-5">
        
        <!-- Summary -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-indigo-600 text-sm"></i>
            خلاصه
          </h3>
          <p id="modalSummary" class="text-sm text-text-primary leading-relaxed"></p>
        </div>
        
        <!-- Description -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug flex items-center gap-2">
            <i class="fa-solid fa-align-left text-purple-600 text-sm"></i>
            توضیحات کامل
          </h3>
          <p id="modalDescription" class="text-sm text-text-primary leading-relaxed"></p>
        </div>
        
        <!-- Tags -->
        <div class="mb-5">
          <h3 class="text-base font-semibold text-text-primary mb-2 leading-snug flex items-center gap-2">
            <i class="fa-solid fa-tags text-pink-600 text-sm"></i>
            برچسب‌ها
          </h3>
          <div id="modalTags" class="flex flex-wrap gap-2"></div>
        </div>
        
        <!-- Badges -->
        <div id="modalBadges" class="flex flex-wrap gap-2 mb-5"></div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-3 text-center border border-blue-100">
            <div class="text-xl font-bold text-blue-600 mb-0.5" id="modalViews"></div>
            <div class="text-xs text-blue-700">بازدید</div>
          </div>
          <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-3 text-center border border-yellow-100">
            <div class="text-xl font-bold text-yellow-600 mb-0.5" id="modalRating"></div>
            <div class="text-xs text-yellow-700">امتیاز</div>
          </div>
          <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-3 text-center border border-green-100">
            <div class="text-xl font-bold text-green-600 mb-0.5" id="modalAttachments"></div>
            <div class="text-xs text-green-700">پیوست</div>
          </div>
          <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-3 text-center border border-indigo-100">
            <div class="text-xl font-bold text-indigo-600 mb-0.5" id="modalDate"></div>
            <div class="text-xs text-indigo-700">تاریخ</div>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <style>
  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-slideUp {
    animation: slideUp 0.3s ease-out;
  }
  
  /* Auto-resize Textarea */
  #queryInput {
    min-height: 24px;
  }
  </style>
  
  <script>
  // داده‌های تجربیات (از PHP به JavaScript)
  const experiences = <?= json_encode($allExperiences, JSON_UNESCAPED_UNICODE) ?>;
  
  // عناصر DOM
  const queryInput = document.getElementById('queryInput');
  const sendBtn = document.getElementById('sendBtn');
  const voiceBtn = document.getElementById('voiceBtn');
  const attachBtn = document.getElementById('attachBtn');
  const chatContainer = document.getElementById('chatContainer');
  const welcomeMessage = document.getElementById('welcomeMessage');
  const searchResults = document.getElementById('searchResults');
  const experienceModal = document.getElementById('experienceModal');
  const closeModal = document.getElementById('closeModal');
  
  // تابع تبدیل اعداد انگلیسی به فارسی
  function toPersianNumber(num) {
    const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    return String(num).replace(/\d/g, digit => persianDigits[digit]);
  }
  
  // Auto-resize Textarea
  queryInput.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 240) + 'px';
  });
  
  // تابع جستجو
  function performSearch() {
    const query = queryInput.value.trim().toLowerCase();
    
    if (query === '') {
      return;
    }
    
    // مخفی کردن پیام خوش‌آمدگویی
    welcomeMessage.classList.add('hidden');
    searchResults.classList.remove('hidden');
    
    // فیلتر تجربیات بر اساس query
    const filteredExperiences = experiences.filter(exp => {
      const searchableText = `${exp.title} ${exp.summary} ${exp.description} ${exp.department} ${exp.author} ${exp.keywords} ${exp.tags.join(' ')}`.toLowerCase();
      return searchableText.includes(query);
    });
    
    // نمایش پیام کاربر
    addUserMessage(query);
    
    // پاک کردن input
    queryInput.value = '';
    queryInput.style.height = 'auto';
    
    // شبیه‌سازی تاخیر AI
    setTimeout(() => {
      if (filteredExperiences.length === 0) {
        addSystemMessage('متأسفانه هیچ نتیجه‌ای یافت نشد. لطفاً کلمات دیگری را امتحان کنید.');
      } else {
        addSystemMessage(`${toPersianNumber(filteredExperiences.length)} تجربه مرتبط یافت شد:`);
        renderCarousel(filteredExperiences);
      }
    }, 800);
  }
  
  // افزودن پیام کاربر
  function addUserMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex justify-end mb-4';
    messageDiv.innerHTML = `
      <div class="max-w-[85%] bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-md">
        <p class="text-sm leading-relaxed">${message}</p>
      </div>
    `;
    searchResults.appendChild(messageDiv);
    
    // Auto-scroll به آخرین پیام
    setTimeout(() => {
      chatContainer.scrollTo({
        top: chatContainer.scrollHeight,
        behavior: 'smooth'
      });
    }, 100);
  }
  
  // افزودن پیام سیستم
  function addSystemMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex justify-start mb-4';
    messageDiv.innerHTML = `
      <div class="max-w-[85%] bg-gray-100 text-text-primary rounded-2xl rounded-bl-sm px-4 py-3">
        <div class="flex items-center gap-2 mb-2">
          <div class="w-6 h-6 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-sparkles text-white text-xs"></i>
          </div>
          <span class="text-xs font-semibold text-text-secondary">دستیار هوشمند</span>
        </div>
        <p class="text-sm leading-relaxed">${message}</p>
      </div>
    `;
    searchResults.appendChild(messageDiv);
    
    // Auto-scroll به آخرین پیام
    setTimeout(() => {
      chatContainer.scrollTo({
        top: chatContainer.scrollHeight,
        behavior: 'smooth'
      });
    }, 100);
  }
  
  // رندر کروسل تجربیات
  function renderCarousel(results) {
    const carouselDiv = document.createElement('div');
    carouselDiv.className = 'mb-4';
    
    const carouselHTML = `
      <div class="overflow-x-auto pb-4 -mx-4 px-4">
        <div class="flex gap-3" style="width: max-content;">
          ${results.map(exp => `
            <div class="w-[280px] bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-200 cursor-pointer" onclick='showExperienceDetails(${JSON.stringify(exp).replace(/'/g, "\\'")})'>
              <!-- Header با گرادیانت -->
              <div class="bg-gradient-to-br from-${exp.department_color}-500 to-${exp.department_color}-600 px-4 py-3">
                <div class="flex items-center justify-between mb-2">
                  <span class="text-xs font-medium text-white/90">${exp.department}</span>
                  <div class="flex items-center gap-1 text-xs text-white font-medium">
                    <i class="fa-solid fa-star text-[10px]"></i>
                    <span>${toPersianNumber(exp.rating)}</span>
                  </div>
                </div>
                <h3 class="text-sm font-bold text-white leading-snug line-clamp-2">${exp.title}</h3>
              </div>
              
              <!-- Body -->
              <div class="p-4">
                <p class="text-xs text-text-secondary leading-relaxed mb-3 line-clamp-3">${exp.summary}</p>
                
                <!-- Meta -->
                <div class="flex items-center justify-between text-xs text-text-muted">
                  <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-user text-[10px]"></i>
                    <span>${exp.author}</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-eye text-[10px]"></i>
                    <span>${toPersianNumber(exp.views)}</span>
                  </div>
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      </div>
    `;
    
    carouselDiv.innerHTML = carouselHTML;
    searchResults.appendChild(carouselDiv);
    
    // Auto-scroll به آخرین محتوا
    setTimeout(() => {
      chatContainer.scrollTo({
        top: chatContainer.scrollHeight,
        behavior: 'smooth'
      });
    }, 100);
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
      `<span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-medium leading-normal">
        <i class="fa-solid fa-tag text-[10px]"></i>
        ${tag}
      </span>`
    ).join('');
    
    // Badges
    const modalBadges = document.getElementById('modalBadges');
    modalBadges.innerHTML = `
      <span class="inline-flex items-center gap-1.5 bg-${exp.department_color}-50 text-${exp.department_color}-700 px-3 py-1.5 rounded-lg text-sm font-medium leading-normal border border-${exp.department_color}-200">
        <i class="fa-solid fa-sitemap text-xs"></i>
        ${exp.department}
      </span>
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium leading-normal border border-blue-200">
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
  
  // رویداد کلیک دکمه ارسال
  sendBtn.addEventListener('click', performSearch);
  
  // رویداد Enter در textarea
  queryInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
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
      queryInput.value = transcript;
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
    voiceBtn.disabled = true;
    voiceBtn.classList.add('opacity-50', 'cursor-not-allowed');
    voiceBtn.title = 'مرورگر شما از جستجوی صوتی پشتیبانی نمی‌کند';
  }
  
  // دکمه الصاق فایل (برای آینده)
  attachBtn.addEventListener('click', () => {
    alert('قابلیت الصاق فایل به زودی اضافه خواهد شد');
  });
  </script>
  
</body>
</html>
