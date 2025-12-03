-- Default folders seeded to match the dashboard documents prototype with hierarchical structure

-- Main folders (parent_id = NULL)
-- توجه: ستون `id` حذف شد تا پایگاه داده آن را به صورت خودکار ایجاد کند (با فرض AUTO_INCREMENT).
INSERT INTO `core_file_folders` (`parent_id`, `module_name`, `name`, `is_public`, `color`, `icon`, `description`, `order`, `created_at`, `updated_at`)
VALUES
  (NULL, NULL, 'اسناد استراتژیک', 0, 'purple', 'fa-solid fa-landmark', 'پوشه اسناد و مدارک استراتژیک شرکت', 1, NOW(), NOW()), -- id = 1
  (NULL, NULL, 'بازاریابی و سوشال', 0, 'pink', 'fa-solid fa-bullhorn', 'پوشه اسناد و فایل‌های بازاریابی', 2, NOW(), NOW()), -- id = 2
  (NULL, NULL, 'مالی و قراردادها', 0, 'green', 'fa-solid fa-chart-line', 'پوشه اسناد مرتبط با فروش', 3, NOW(), NOW()), -- id = 3
  (NULL, NULL, 'فروش', 0, 'blue', 'fa-solid fa-chart-line', 'پوشه اسناد مرتبط با فروش', 4, NOW(), NOW()), -- id = 4
  (NULL, NULL, 'فنی و مهندسی', 0, 'slate', 'fa-solid fa-gears', 'پوشه مستندات و فایل‌های فنی', 5, NOW(), NOW()), -- id = 5
  (NULL, NULL, 'متفرقه', 0, 'amber', 'fa-solid fa-folder-tree', 'پوشه مستندات و فایل‌های متفرقه', 6, NOW(), NOW()), -- id = 6
  (NULL, NULL, 'آرشیو', 0, 'slate', 'fa-solid fa-box-archive', 'پوشه مستندات و فایل‌های آرشیو شده', 7, NOW(), NOW()); -- id = 7

-- **توجه مهم:** در کوئری زیر فرض بر این است که پوشه‌های اصلی به ترتیب از `id = 1` تا `id = 7` ایجاد شده‌اند.
-- اگر جدول از قبل داده دارد یا اگر `id` به صورت دستی تعیین می‌شود، باید `id`های صحیح را بررسی و جایگزین کنید.
-- در این مثال، فرض شده است:
-- 'اسناد استراتژیک' -> id = 1
-- 'بازاریابی و سوشال' -> id = 2
-- 'مالی و قراردادها' -> id = 3
-- 'فروش' -> id = 4
-- 'فنی و مهندسی' -> id = 5

-- Sub-folders for 'اسناد استراتژیک' (parent_id = 1)
INSERT INTO `core_file_folders` (`parent_id`, `module_name`, `name`, `is_public`, `color`, `icon`, `description`, `order`, `created_at`, `updated_at`)
VALUES
  (1, NULL, 'منابع انسانی', 0, 'blue', 'fa-solid fa-users', 'زیرپوشه منابع انسانی', 1, NOW(), NOW()),
  (1, NULL, 'مالی', 0, 'green', 'fa-solid fa-coins', 'زیرپوشه مالی', 2, NOW(), NOW()),
  (1, NULL, 'حقوقی', 0, 'slate', 'fa-solid fa-scale-balanced', 'زیرپوشه حقوقی', 3, NOW(), NOW()),
  (1, NULL, 'مدیریتی', 0, 'amber', 'fa-solid fa-briefcase', 'زیرپوشه مدیریتی', 4, NOW(), NOW());

-- Sub-folders for 'بازاریابی و سوشال' (parent_id = 2)
INSERT INTO `core_file_folders` (`parent_id`, `module_name`, `name`, `is_public`, `color`, `icon`, `description`, `order`, `created_at`, `updated_at`)
VALUES
  (2, NULL, 'کمپین‌ها', 0, 'pink', 'fa-solid fa-rocket', 'زیرپوشه کمپین‌ها', 1, NOW(), NOW()),
  (2, NULL, 'برندینگ', 0, 'purple', 'fa-solid fa-palette', 'زیرپوشه برندینگ', 2, NOW(), NOW()),
  (2, NULL, 'شبکه‌های اجتماعی', 0, 'blue', 'fa-solid fa-share-nodes', 'زیرپوشه شبکه‌های اجتماعی', 3, NOW(), NOW());

-- Sub-folders for 'فروش' (parent_id = 4)
INSERT INTO `core_file_folders` (`parent_id`, `module_name`, `name`, `is_public`, `color`, `icon`, `description`, `order`, `created_at`, `updated_at`)
VALUES
  (4, NULL, 'قراردادها', 0, 'green', 'fa-solid fa-file-signature', 'زیرپوشه قراردادها', 1, NOW(), NOW()),
  (4, NULL, 'پیشنهادات', 0, 'amber', 'fa-solid fa-file-invoice', 'زیرپوشه پیشنهادات', 2, NOW(), NOW()),
  (4, NULL, 'گزارشات فروش', 0, 'blue', 'fa-solid fa-chart-bar', 'زیرپوشه گزارشات فروش', 3, NOW(), NOW());

-- Sub-folders for 'فنی و مهندسی' (parent_id = 5)
-- اصلاح: `parent_id` از 4 به 5 تغییر یافت تا به 'فنی و مهندسی' ارجاع دهد.
INSERT INTO `core_file_folders` (`parent_id`, `module_name`, `name`, `is_public`, `color`, `icon`, `description`, `order`, `created_at`, `updated_at`)
VALUES
  (5, NULL, 'مشخصات فنی', 0, 'blue', 'fa-solid fa-clipboard-list', 'زیرپوشه مشخصات فنی', 1, NOW(), NOW()),
  (5, NULL, 'دفترچه راهنما', 0, 'green', 'fa-solid fa-book', 'زیرپوشه دفترچه راهنما', 2, NOW(), NOW()),
  (5, NULL, 'نقشه‌ها', 0, 'purple', 'fa-solid fa-drafting-compass', 'زیرپوشه نقشه‌ها', 3, NOW(), NOW());
