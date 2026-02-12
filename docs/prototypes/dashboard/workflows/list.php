<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'مدیریت فرایندها';
$currentPage = 'workflow';

// دکمه‌های عملیاتی
$actionButtons = [
    ['label' => 'ایجاد فرایند جدید', 'url' => '/dashboard/workflows/create.php', 'icon' => 'fa-solid fa-plus', 'type' => 'primary']
];

// داده‌های صفحه
$breadcrumbs = [
    ['label' => 'خانه', 'url' => '/dashboard/index.php'],
    ['label' => 'مدیریت وظایف', 'url' => '/dashboard/workflows/index.php'],
    ['label' => 'لیست فرایندها'],
];

// داده‌های نمونه فرایندها (در پروژه واقعی از دیتابیس)
$workflows = [
    [
        'id' => 1,
        'name' => 'اینسپکشن',
        'slug' => 'inception',
        'description' => 'فرایند بررسی و ارزیابی اولیه مشتریان جدید',
        'department' => 'فروش',
        'department_color' => 'emerald',
        'owner' => 'علی احمدی',
        'is_active' => true,
        'states' => [
            ['name' => 'آزمون ورودی', 'color' => '#E0F2F1', 'tasks_count' => 0, 'position' => 'start'],
            ['name' => 'مشاوره خصوصی', 'color' => '#E3F2FD', 'tasks_count' => 5, 'position' => 'middle'],
            ['name' => 'منتظر پرداخت', 'color' => '#FFF3E0', 'tasks_count' => 7, 'position' => 'middle'],
            ['name' => 'پیش پرداخت شده', 'color' => '#E8F5E9', 'tasks_count' => 6, 'position' => 'middle', 'is_flag' => true],
            ['name' => 'تکمیل شده', 'color' => '#C8E6C9', 'tasks_count' => null, 'position' => 'final-success', 'is_final' => true],
            ['name' => 'لغو شده', 'color' => '#FFCDD2', 'tasks_count' => null, 'position' => 'final-failed', 'is_final' => true],
        ],
    ],
    [
        'id' => 2,
        'name' => 'مسیریابی',
        'slug' => 'router-campaign',
        'description' => 'فرایند هدایت و مسیردهی کمپین‌های تبلیغاتی',
        'department' => 'عمومی',
        'department_color' => 'slate',
        'owner' => 'مریم رضایی',
        'is_active' => true,
        'states' => [
            ['name' => 'سرنخ', 'color' => '#E0F2F1', 'tasks_count' => 9, 'position' => 'start'],
            ['name' => 'ارسال آفر', 'color' => '#FFF8E1', 'tasks_count' => 38, 'position' => 'middle', 'is_flag' => true],
            ['name' => 'در حال مذاکره', 'color' => '#E3F2FD', 'tasks_count' => 2, 'position' => 'middle'],
            ['name' => 'موفق', 'color' => '#C8E6C9', 'tasks_count' => null, 'position' => 'final-success', 'is_final' => true],
            ['name' => 'ناموفق', 'color' => '#FFCDD2', 'tasks_count' => null, 'position' => 'final-failed', 'is_final' => true],
        ],
    ],
    [
        'id' => 3,
        'name' => 'کمپین فیدبک',
        'slug' => 'feedback-campaign',
        'description' => 'فرایند جمع‌آوری و پیگیری بازخورد مشتریان',
        'department' => 'عمومی',
        'department_color' => 'slate',
        'owner' => 'سارا محمدی',
        'is_active' => true,
        'states' => [
            ['name' => 'سرنخ', 'color' => '#E0F2F1', 'tasks_count' => 0, 'position' => 'start'],
            ['name' => 'ارسال آفر', 'color' => '#FFF8E1', 'tasks_count' => 0, 'position' => 'middle', 'is_flag' => true],
            ['name' => 'در حال مذاکره', 'color' => '#E3F2FD', 'tasks_count' => 0, 'position' => 'middle'],
            ['name' => 'تکمیل', 'color' => '#C8E6C9', 'tasks_count' => null, 'position' => 'final-success', 'is_final' => true],
            ['name' => 'انصراف', 'color' => '#FFCDD2', 'tasks_count' => null, 'position' => 'final-failed', 'is_final' => true],
        ],
    ],
    [
        'id' => 4,
        'name' => 'سبد رها شده',
        'slug' => 'abandoned-cart',
        'description' => 'فرایند پیگیری سبدهای خرید رها شده',
        'department' => 'فروش',
        'department_color' => 'emerald',
        'owner' => 'رضا کریمی',
        'is_active' => true,
        'states' => [
            ['name' => 'سبد پرداخت نشده', 'color' => '#FFEBEE', 'tasks_count' => 17, 'position' => 'start'],
            ['name' => 'در حال مذاکره', 'color' => '#E3F2FD', 'tasks_count' => 957, 'position' => 'middle'],
            ['name' => 'ارسال آفر', 'color' => '#FFF8E1', 'tasks_count' => 8, 'position' => 'middle', 'is_flag' => true],
            ['name' => 'خرید شد', 'color' => '#C8E6C9', 'tasks_count' => null, 'position' => 'final-success', 'is_final' => true],
            ['name' => 'رها شد', 'color' => '#FFCDD2', 'tasks_count' => null, 'position' => 'final-failed', 'is_final' => true],
        ],
    ],
    [
        'id' => 5,
        'name' => 'استخدام',
        'slug' => 'recruitment',
        'description' => 'فرایند جذب و استخدام نیروی انسانی',
        'department' => 'منابع انسانی',
        'department_color' => 'pink',
        'owner' => 'فاطمه نوری',
        'is_active' => false,
        'states' => [
            ['name' => 'دریافت رزومه', 'color' => '#E3F2FD', 'tasks_count' => 0, 'position' => 'start'],
            ['name' => 'بررسی اولیه', 'color' => '#FFF3E0', 'tasks_count' => 0, 'position' => 'middle'],
            ['name' => 'مصاحبه', 'color' => '#F3E5F5', 'tasks_count' => 0, 'position' => 'middle', 'is_flag' => true],
            ['name' => 'آزمون فنی', 'color' => '#E8EAF6', 'tasks_count' => 0, 'position' => 'middle'],
            ['name' => 'استخدام شده', 'color' => '#C8E6C9', 'tasks_count' => null, 'position' => 'final-success', 'is_final' => true],
            ['name' => 'رد شده', 'color' => '#FFCDD2', 'tasks_count' => null, 'position' => 'final-failed', 'is_final' => true],
        ],
    ],
];

// لود کامپوننت Head
component('head', ['pageTitle' => $pageTitle]);
?>

<body class="bg-bg-secondary">
  <div class="flex min-h-screen">
    <?php component('workflow-sidebar', ['currentPage' => $currentPage]); ?>
    <main class="flex-1 flex flex-col">
      <?php component('module-header', [
          'pageTitle' => $pageTitle,
          'breadcrumbs' => $breadcrumbs,
          'actionButtons' => $actionButtons
      ]); ?>
      <div class="flex-1 p-6 lg:p-8">
        
        <!-- Filters & Search -->
        <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Search -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    <i class="fa-solid fa-search"></i>
                  </label>
                  <input type="text" 
                         class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                         placeholder="جستجوی فرایند..."
                         data-search=".workflow-row">
                </div>
              </div>
            </div>
            
            <!-- Department Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    دپارتمان
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="department">
                    <option value="">همه دپارتمان‌ها</option>
                    <option value="فروش">فروش</option>
                    <option value="عمومی">عمومی</option>
                    <option value="منابع انسانی">منابع انسانی</option>
                    <option value="مالی">مالی</option>
                    <option value="فنی">فنی</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Status Filter -->
            <div class="md:col-span-1">
              <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-indigo-600 focus-within:shadow-focus transition-all duration-200">
                <div class="flex items-stretch">
                  <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                    وضعیت
                  </label>
                  <select class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal" data-filter="status">
                    <option value="">همه</option>
                    <option value="active">فعال</option>
                    <option value="inactive">غیرفعال</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Reset Filters -->
            <div class="md:col-span-1 flex items-center">
              <button type="button" class="text-sm text-text-muted hover:text-primary transition-colors leading-normal" onclick="resetFilters()">
                <i class="fa-solid fa-rotate-right ml-1"></i>
                پاک کردن فیلترها
              </button>
            </div>
            
          </div>
        </div>
        
        <!-- Workflows List -->
        <div class="space-y-3">
          <?php foreach ($workflows as $workflow): 
            // جدا کردن مراحل معمولی از مراحل پایانی
            $regularStates = array_filter($workflow['states'], fn($s) => !isset($s['is_final']) || !$s['is_final']);
            $finalStates = array_filter($workflow['states'], fn($s) => isset($s['is_final']) && $s['is_final']);
          ?>
          <div class="bg-bg-primary border border-border-light rounded-xl overflow-hidden hover:shadow-sm transition-all duration-200 workflow-row group"
               data-department="<?= $workflow['department'] ?>"
               data-status="<?= $workflow['is_active'] ? 'active' : 'inactive' ?>">
            
            <div class="px-4 py-3">
              <!-- Single Row: All Info -->
              <div class="flex items-center gap-4">
                
                <!-- Workflow Name & Status -->
                <div class="flex items-center gap-2.5 w-[160px] flex-shrink-0">
                  <?php if ($workflow['is_active']): ?>
                  <span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0" title="فعال"></span>
                  <?php else: ?>
                  <span class="w-2 h-2 bg-gray-300 rounded-full flex-shrink-0" title="غیرفعال"></span>
                  <?php endif; ?>
                  <h3 class="text-sm font-semibold text-text-primary leading-snug truncate" title="<?= $workflow['slug'] ?>"><?= $workflow['name'] ?></h3>
                </div>
                
                <!-- Department Badge -->
                <span class="inline-flex items-center bg-<?= $workflow['department_color'] ?>-50 text-<?= $workflow['department_color'] ?>-700 px-2 py-0.5 rounded text-xs font-medium leading-normal w-[80px] justify-center flex-shrink-0">
                  <?= $workflow['department'] ?>
                </span>
                
                <!-- Owner -->
                <span class="inline-flex items-center gap-1.5 text-xs text-text-muted leading-normal w-[100px] flex-shrink-0" title="مدیر فرایند">
                  <i class="fa-solid fa-user-tie text-[10px]"></i>
                  <span class="truncate"><?= $workflow['owner'] ?></span>
                </span>
                
                <!-- States Pipeline - Arrow Shape Connected (RTL) -->
                <div class="flex-1 flex items-center justify-end">
                  <div class="inline-flex items-stretch flex-row-reverse">
                    <?php if (!empty($finalStates)): ?>
                    <!-- Final States Dropdown (Last in flow = leftmost in RTL) -->
                    <div class="relative flex-shrink-0 state-arrow" style="margin-right: -18px;">
                      <button type="button" class="final-states-btn relative h-[50px] min-w-[90px] flex flex-col items-center justify-center px-4 cursor-pointer hover:brightness-95 transition-all"
                              onclick="toggleFinalStates(this)">
                        <!-- Background with arrow shape - Last state: rounded left, arrow right -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 50" preserveAspectRatio="none">
                          <path d="M8,0 C4,0 0,4 0,8 L0,42 C0,46 4,50 8,50 L80,50 L92,25 L80,0 Z" fill="#E8F5E9" />
                        </svg>
                        <div class="relative z-10 text-center mr-2">
                          <div class="text-[11px] font-medium text-gray-700 leading-tight flex items-center gap-1">
                            <span>پایان</span>
                            <i class="fa-solid fa-chevron-down text-[8px] transition-transform"></i>
                          </div>
                          <div class="text-[10px] text-gray-500 leading-none mt-0.5"><?= count($finalStates) ?> حالت</div>
                        </div>
                      </button>
                      <!-- Dropdown -->
                      <div class="final-states-dropdown hidden absolute top-full right-0 mt-1 bg-white border border-border-light rounded-lg shadow-lg z-20 min-w-[140px] py-1">
                        <?php foreach ($finalStates as $finalState): ?>
                        <div class="px-3 py-2 flex items-center gap-2 text-sm hover:bg-gray-50 cursor-pointer">
                          <?php if ($finalState['position'] === 'final-success'): ?>
                          <i class="fa-solid fa-check-circle text-green-600 text-xs"></i>
                          <?php else: ?>
                          <i class="fa-solid fa-times-circle text-red-500 text-xs"></i>
                          <?php endif; ?>
                          <span class="text-text-primary"><?= $finalState['name'] ?></span>
                        </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php $stateIndex = 0; $totalRegular = count($regularStates); foreach (array_reverse($regularStates) as $state): 
                      $isLast = $stateIndex === $totalRegular - 1; // Last in reversed = First in original (rightmost)
                      $isFirst = $stateIndex === 0; // First in reversed = Last in original (before final)
                      $hasFlag = isset($state['is_flag']) && $state['is_flag'];
                      $taskCount = $state['tasks_count'] ?? 0;
                      // همه مراحل به جز راست‌ترین margin منفی دارند
                      $needsMargin = !$isLast;
                    ?>
                      <!-- Arrow-shaped State (RTL) -->
                      <div class="relative flex-shrink-0 state-arrow" style="<?= $needsMargin ? 'margin-right: -18px;' : '' ?>">
                        <div class="relative h-[50px] min-w-[90px] flex flex-col items-center justify-center px-4">
                          <!-- Background with arrow shape (RTL - arrow points left) -->
                          <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 50" preserveAspectRatio="none">
                            <?php if ($isLast): ?>
                            <!-- First state (rightmost): rounded right, arrow left -->
                            <path d="M92,0 L20,0 L8,25 L20,50 L92,50 C96,50 100,46 100,42 L100,8 C100,4 96,0 92,0 Z" fill="<?= $state['color'] ?>" />
                            <?php else: ?>
                            <!-- Middle/Last states: arrow both sides -->
                            <path d="M100,0 L20,0 L8,25 L20,50 L100,50 L88,25 Z" fill="<?= $state['color'] ?>" />
                            <?php endif; ?>
                          </svg>
                          <!-- Content -->
                          <div class="relative z-10 text-center">
                            <div class="text-[11px] font-medium text-text-primary leading-tight truncate max-w-[70px]"><?= $state['name'] ?></div>
                            <div class="text-[13px] font-bold text-text-primary leading-none mt-0.5"><?= $taskCount ?></div>
                          </div>
                        </div>
                      </div>
                    <?php $stateIndex++; endforeach; ?>
                  </div>
                </div>
                
                <!-- Actions - Always visible -->
                <div class="flex items-center gap-1 flex-shrink-0">
                  <!-- Embed Code -->
                  <button 
                     class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all duration-200"
                     title="کد جاسازی (<?= $workflow['slug'] ?>)"
                     onclick="showEmbedCode('<?= $workflow['slug'] ?>')">
                    <i class="fa-solid fa-code text-xs"></i>
                  </button>
                  
                  <!-- Edit -->
                  <a href="/dashboard/workflows/edit.php?id=<?= $workflow['id'] ?>" 
                     class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all duration-200"
                     title="ویرایش">
                    <i class="fa-solid fa-pen text-xs"></i>
                  </a>
                  
                  <!-- Delete -->
                  <button 
                     class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200"
                     title="حذف"
                     onclick="openDeleteModal(<?= $workflow['id'] ?>, '<?= $workflow['name'] ?>')">
                    <i class="fa-solid fa-trash text-xs"></i>
                  </button>
                </div>
                
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        
      </div>
    </main>
  </div>
  
  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[500px] w-full">
      <!-- Header -->
      <div class="px-6 py-5 border-b border-border-light">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-text-primary leading-snug">حذف فرایند</h3>
            <p class="text-sm text-text-secondary leading-normal mt-1">این عمل قابل بازگشت نیست</p>
          </div>
        </div>
      </div>
      
      <!-- Body -->
      <div class="p-6">
        <p class="text-base text-text-primary leading-relaxed mb-4">
          آیا از حذف فرایند <strong id="deleteWorkflowName" class="text-red-600"></strong> اطمینان دارید؟
        </p>
        <p class="text-sm text-text-secondary leading-relaxed mb-6">
          برای تایید حذف، کلمه <strong class="text-red-600 font-mono">حذف</strong> را در کادر زیر وارد کنید:
        </p>
        
        <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-red-500 focus-within:shadow-focus transition-all duration-200">
          <div class="flex items-stretch">
            <label class="bg-bg-label border-l border-border-light min-w-[120px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
              کلمه تایید
            </label>
            <input type="text" id="deleteConfirmInput"
                   class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                   placeholder="حذف"
                   autocomplete="off">
          </div>
        </div>
        
        <input type="hidden" id="deleteWorkflowId" value="">
      </div>
      
      <!-- Footer -->
      <div class="px-6 py-4 border-t border-border-light flex items-center justify-end gap-3">
        <button type="button" 
                onclick="closeDeleteModal()"
                class="bg-bg-secondary text-text-secondary border border-border-medium px-5 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal">
          انصراف
        </button>
        <button type="button" 
                id="confirmDeleteBtn"
                disabled
                class="bg-red-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-red-700 transition-all duration-200 flex items-center gap-2 text-base leading-normal disabled:opacity-50 disabled:cursor-not-allowed">
          <i class="fa-solid fa-trash"></i>
          <span>حذف فرایند</span>
        </button>
      </div>
    </div>
  </div>
  
  <!-- Embed Code Modal -->
  <div id="embedModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-bg-primary rounded-3xl shadow-lg max-w-[600px] w-full">
      <!-- Header -->
      <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
        <h3 class="text-lg font-semibold text-text-primary leading-snug">کد جاسازی فرایند</h3>
        <button onclick="closeEmbedModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary transition-colors">
          <i class="fa-solid fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Body -->
      <div class="p-6">
        <p class="text-sm text-text-secondary leading-relaxed mb-4">
          از کد زیر برای جاسازی این فرایند در سیستم‌های دیگر استفاده کنید:
        </p>
        
        <div class="bg-slate-900 rounded-xl p-4 overflow-x-auto">
          <code id="embedCode" class="text-sm text-green-400 font-mono whitespace-pre"></code>
        </div>
        
        <button onclick="copyEmbedCode()" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 text-sm leading-normal">
          <i class="fa-solid fa-copy"></i>
          <span>کپی کد</span>
        </button>
      </div>
    </div>
  </div>
  
  <script src="<?= asset('js/utils.js') ?>"></script>
  <script src="<?= asset('js/app.js') ?>"></script>
  
  <?php component('persian-numbers'); ?>
  
  <script>
  // Delete Modal
  function openDeleteModal(id, name) {
    document.getElementById('deleteWorkflowId').value = id;
    document.getElementById('deleteWorkflowName').textContent = name;
    document.getElementById('deleteConfirmInput').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
    document.getElementById('deleteModal').classList.remove('hidden');
  }
  
  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
  }
  
  document.getElementById('deleteConfirmInput').addEventListener('input', function() {
    const isValid = this.value.trim() === 'حذف';
    document.getElementById('confirmDeleteBtn').disabled = !isValid;
  });
  
  document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    const id = document.getElementById('deleteWorkflowId').value;
    // در پروژه واقعی: ارسال درخواست به سرور
    console.log('Deleting workflow:', id);
    closeDeleteModal();
    // نمایش پیام موفقیت
    alert('فرایند با موفقیت حذف شد');
    location.reload();
  });
  
  // Embed Code Modal
  function showEmbedCode(slug) {
    const code = `<workflow-widget slug="${slug}" />\n\n<!-- یا استفاده از API -->\nGET /api/workflows/${slug}/tasks`;
    document.getElementById('embedCode').textContent = code;
    document.getElementById('embedModal').classList.remove('hidden');
  }
  
  function closeEmbedModal() {
    document.getElementById('embedModal').classList.add('hidden');
  }
  
  function copyEmbedCode() {
    const code = document.getElementById('embedCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
      alert('کد کپی شد');
    });
  }
  
  // Filters
  document.querySelectorAll('[data-filter]').forEach(select => {
    select.addEventListener('change', applyFilters);
  });
  
  function applyFilters() {
    const departmentFilter = document.querySelector('[data-filter="department"]').value.toLowerCase();
    const statusFilter = document.querySelector('[data-filter="status"]').value.toLowerCase();
    
    document.querySelectorAll('.workflow-row').forEach(row => {
      const department = row.dataset.department.toLowerCase();
      const status = row.dataset.status.toLowerCase();
      
      const matchDepartment = !departmentFilter || department.includes(departmentFilter);
      const matchStatus = !statusFilter || status === statusFilter;
      
      row.style.display = (matchDepartment && matchStatus) ? '' : 'none';
    });
  }
  
  function resetFilters() {
    document.querySelector('[data-filter="department"]').value = '';
    document.querySelector('[data-filter="status"]').value = '';
    document.querySelectorAll('.workflow-row').forEach(row => {
      row.style.display = '';
    });
  }
  
  // Search
  const searchInput = document.querySelector('[data-search]');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase();
      document.querySelectorAll('.workflow-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }
  
  // Close modals on backdrop click
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
  });
  document.getElementById('embedModal').addEventListener('click', function(e) {
    if (e.target === this) closeEmbedModal();
  });
  
  // Final States Dropdown Toggle
  function toggleFinalStates(btn) {
    const dropdown = btn.nextElementSibling;
    const icon = btn.querySelector('.fa-chevron-down');
    const isOpen = !dropdown.classList.contains('hidden');
    
    // Close all other dropdowns first
    document.querySelectorAll('.final-states-dropdown').forEach(d => {
      d.classList.add('hidden');
      d.previousElementSibling.querySelector('.fa-chevron-down')?.classList.remove('rotate-180');
    });
    
    if (!isOpen) {
      dropdown.classList.remove('hidden');
      icon?.classList.add('rotate-180');
    }
  }
  
  // Close dropdowns on outside click
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.final-states-btn')) {
      document.querySelectorAll('.final-states-dropdown').forEach(d => {
        d.classList.add('hidden');
        d.previousElementSibling.querySelector('.fa-chevron-down')?.classList.remove('rotate-180');
      });
    }
  });
  </script>
  
</body>
</html>
