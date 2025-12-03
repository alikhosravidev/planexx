<?php
/**
 * Tags Management Page
 * مدیریت برچسب ها
 */

// تعریف Base URL برای رفع مشکل trailing slash
$baseUrl = '/core/dashboard';

$tagManager  = new TagManager();
$tags        = $tagManager->getAllTags();
$stats       = $tagManager->getTagsStats();
$popularTags = $tagManager->getPopularTags(5);

// تابع تبدیل به فارسی
function toPersianNumber($number)
{
    return DateHelper::convertToFarsiNumbers((string)$number);
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت برچسب‌ها - سون لرن</title>

    <base href="<?php echo $baseUrl; ?>/">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="/campaign/assets/js/tailwind-config.js"></script>

    <style>
        body, button, input, textarea, select {
            font-family: 'Sahel', sans-serif !important;
        }

        /* انیمیشن برای نمایش/مخفی شدن ردیف ها */
        .tag-row {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .tag-row[style*="display: none"] {
            opacity: 0;
            transform: translateX(-10px);
        }

        /* هایلایت نتایج جستجو */
        @keyframes highlight {
            0%, 100% { background-color: transparent; }
            50% { background-color: rgba(59, 130, 246, 0.1); }
        }

        .search-highlight {
            animation: highlight 1s ease;
        }

        /* استایل بهتر برای دکمه محبوب ترین ها */
        #sortByPopular.active {
            background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);
            border-color: #FB923C;
            box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
        }
    </style>
</head>
<body class="bg-bg-secondary">

    <?php include __DIR__ . '/../_impersonation_banner.php'; ?>

    <!-- Header -->
    <header class="bg-bg-primary border-b border-border-light sticky top-0 z-40 shadow-sm">
        <div class="max-w-[1400px] mx-auto px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="/core/dashboard/index.php" class="w-10 h-10 flex items-center justify-center hover:bg-bg-secondary rounded-lg transition-all">
                        <i class="fa-solid fa-arrow-right text-text-primary"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-text-primary leading-snug">مدیریت برچسب ها</h1>
                        <p class="text-sm text-text-secondary leading-normal">دسته بندی و برچسب گذاری موجودیت ها</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="openAddTagModal()" class="bg-primary text-white px-5 py-2 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        افزودن برچسب
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center gap-3 hover:bg-bg-secondary px-2 py-2 rounded-lg transition-all duration-200">
                            <?php if (!empty($currentUser['avatar'])): ?>
                            <img src="/core/storage/avatars/<?= $currentUser['avatar'] ?>"
                                 alt="<?= $currentUser['first_name'] ?>"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-primary/20">
                            <?php else: ?>
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
                                <?= mb_substr($currentUser['first_name'], 0, 1) ?>
                            </div>
                            <?php endif; ?>
                            <i class="fa-solid fa-chevron-down text-xs text-text-muted hidden md:block"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-dropdown" class="absolute left-0 mt-2 w-56 bg-bg-primary border border-border-light rounded-xl shadow-lg opacity-0 invisible transition-all duration-200 overflow-hidden z-50">
                            <div class="p-3 border-b border-border-light">
                                <div class="text-sm font-semibold text-text-primary leading-normal"><?= $currentUser['first_name'] . ' ' . $currentUser['last_name'] ?></div>
                                <div class="text-xs text-text-secondary leading-normal"><?= $currentUser['mobile'] ?></div>
                            </div>
                            <div class="py-2">
                                <a href="/core/panel/" class="flex items-center gap-3 px-3xl py-2 text-sm text-text-primary hover:bg-bg-secondary transition-all duration-200">
                                    <i class="fa-solid fa-user w-5"></i>
                                    <span>پروفایل من</span>
                                </a>
                                <a href="index.php" class="flex items-center gap-3 px-3xl py-2 text-sm text-text-primary hover:bg-bg-secondary transition-all duration-200">
                                    <i class="fa-solid fa-home w-5"></i>
                                    <span>داشبورد</span>
                                </a>
                                <a href="users.php" class="flex items-center gap-3 px-3xl py-2 text-sm text-text-primary hover:bg-bg-secondary transition-all duration-200">
                                    <i class="fa-solid fa-users-gear w-5"></i>
                                    <span>مدیریت کاربران</span>
                                </a>
                            </div>
                            <div class="border-t border-border-light py-2">
                                <button onclick="logout()" class="w-full flex items-center gap-3 px-3xl py-2 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                                    <span>خروج از حساب</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-[1400px] mx-auto px-8 py-6">

        <!-- آمار -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 border border-border-light">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-text-secondary">کل برچسب ها</span>
                    <i class="fa-solid fa-tags text-primary text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-text-primary"><?php echo toPersianNumber($stats['total_tags'] ?? 0); ?></div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-border-light">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-text-secondary">موجودیت های برچسب زده</span>
                    <i class="fa-solid fa-bookmark text-green-600 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-text-primary"><?php echo toPersianNumber($stats['tagged_entities_count'] ?? 0); ?></div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-border-light">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-text-secondary">انواع موجودیت</span>
                    <i class="fa-solid fa-layer-group text-purple-600 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-text-primary"><?php echo toPersianNumber($stats['entity_types_count'] ?? 0); ?></div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-border-light">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-text-secondary">پرکاربردترین</span>
                    <i class="fa-solid fa-fire text-orange-600 text-xl"></i>
                </div>
                <div class="text-lg font-bold text-text-primary truncate">
                    <?php
                    if (!empty($popularTags)) {
                        echo htmlspecialchars($popularTags[0]['name']);
                    } else {
                        echo '-';
                    }
?>
                </div>
            </div>
        </div>

        <!-- جستجو و فیلتر -->
        <div class="bg-white rounded-xl p-5 border border-border-light mb-6">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted">
                        <i class="fa-solid fa-search"></i>
                    </div>
                    <input type="text" id="searchInput"
                           class="w-full pl-4 pr-11 py-2.5 border border-border-medium rounded-lg outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="جستجو در نام، توضیحات، اسلاگ..."
                           oninput="filterTags(this.value)">
                </div>

                <!-- دکمه محبوب ترین ها -->
                <button id="sortByPopular" onclick="toggleSort()"
                        class="group px-4 py-2.5 border border-border-medium rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-all flex items-center gap-2"
                        title="مرتب سازی بر اساس محبوبیت">
                    <i class="fa-solid fa-fire text-text-muted group-hover:text-orange-600 transition-colors"></i>
                    <span class="text-sm font-medium text-text-secondary group-hover:text-orange-700 transition-colors">محبوب ترین</span>
                </button>

                <!-- دکمه ریست -->
                <button onclick="resetFilters()"
                        class="px-4 py-2.5 border border-border-medium rounded-lg hover:bg-bg-secondary transition-all"
                        title="نمایش همه">
                    <i class="fa-solid fa-rotate-right ml-2"></i>
                    همه
                </button>
            </div>

            <!-- راهنمای جستجو -->
            <div class="mt-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-text-muted">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>جستجو در نام برچسب، توضیحات و اسلاگ انجام می شود</span>
                </div>
                <div id="resultsCount" class="text-xs text-text-secondary font-medium"></div>
            </div>
        </div>

        <!-- لیست برچسب ها -->
        <div class="bg-white rounded-xl border border-border-light overflow-hidden">
            <table class="w-full">
                <thead class="bg-bg-secondary border-b border-border-light">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary">برچسب</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-text-secondary">توضیحات</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-text-secondary">تعداد استفاده</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-text-secondary">ایجاد کننده</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-text-secondary">عملیات</th>
                    </tr>
                </thead>
                <tbody id="tagsTableBody">
                    <?php foreach ($tags as $tag): ?>
                    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors tag-row"
                        data-tag-name="<?php echo htmlspecialchars($tag['name']); ?>"
                        data-tag-slug="<?php echo htmlspecialchars($tag['slug']); ?>"
                        data-tag-description="<?php echo htmlspecialchars($tag['description'] ?? ''); ?>"
                        data-tag-usage="<?php echo $tag['usage_count'] ?? 0; ?>">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium"
                                  style="background-color: <?php echo htmlspecialchars($tag['color']); ?>20; color: <?php echo htmlspecialchars($tag['color']); ?>;">
                                <i class="fa-solid fa-tag"></i>
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-text-secondary">
                            <?php echo htmlspecialchars($tag['description'] ?? '-'); ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-700 rounded-full text-sm font-bold">
                                <?php echo toPersianNumber($tag['usage_count'] ?? 0); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-text-secondary">
                            <?php echo htmlspecialchars($tag['creator_name'] ?? '-'); ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editTag(<?php echo $tag['id']; ?>)"
                                        class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button onclick="deleteTag(<?php echo $tag['id']; ?>)"
                                        class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($tags)): ?>
            <div class="p-12 text-center">
                <i class="fa-solid fa-tags text-6xl text-gray-300 mb-4"></i>
                <p class="text-text-secondary">هنوز هیچ برچسبی ایجاد نشده است</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal افزودن/ویرایش -->
    <div id="tagModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                <h3 id="modalTitle" class="text-lg font-semibold text-text-primary">افزودن برچسب جدید</h3>
                <button onclick="closeTagModal()" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form id="tagForm" class="p-6 space-y-4">
                <input type="hidden" id="tagId" name="tag_id">

                <!-- نام -->
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-2">نام برچسب</label>
                    <input type="text" id="tagName" name="name" required
                           class="w-full px-4 py-2.5 border border-border-medium rounded-lg outline-none focus:border-primary transition-all"
                           placeholder="مثلاً: مدرس برتر">
                </div>

                <!-- رنگ -->
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-2">رنگ</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="tagColor" name="color" value="#3B82F6"
                               class="w-16 h-10 rounded-lg cursor-pointer border border-border-medium">
                        <input type="text" id="tagColorHex" value="#3B82F6"
                               class="flex-1 px-4 py-2.5 border border-border-medium rounded-lg outline-none focus:border-primary transition-all"
                               placeholder="#3B82F6" onchange="document.getElementById('tagColor').value = this.value">
                    </div>
                </div>

                <!-- توضیحات -->
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-2">توضیحات</label>
                    <textarea id="tagDescription" name="description" rows="3"
                              class="w-full px-4 py-2.5 border border-border-medium rounded-lg outline-none focus:border-primary transition-all resize-none"
                              placeholder="توضیحات اختیاری..."></textarea>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="button" onclick="saveTag()"
                            class="flex-1 bg-primary text-white px-4 py-2.5 rounded-lg font-medium hover:-translate-y-0.5 hover:shadow-button transition-all">
                        <i class="fa-solid fa-check ml-2"></i>
                        ذخیره
                    </button>
                    <button type="button" onclick="closeTagModal()"
                            class="px-4 py-2.5 border border-border-medium rounded-lg hover:bg-bg-secondary transition-all">
                        انصراف
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/tags.js"></script>
    <script>
        // همگام سازی color picker با input
        document.getElementById('tagColor').addEventListener('input', function() {
            document.getElementById('tagColorHex').value = this.value.toUpperCase();
        });

        // User Dropdown Toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('opacity-0');
                userDropdown.classList.toggle('invisible');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('opacity-0');
                    userDropdown.classList.add('invisible');
                }
            });
        }

        // Logout function
        function logout() {
            if (confirm('آیا مطمئن هستید که می خواهید از سیستم خارج شوید؟')) {
                window.location.href = '/core/auth/api/logout.php';
            }
        }
    </script>
</body>
</html>
