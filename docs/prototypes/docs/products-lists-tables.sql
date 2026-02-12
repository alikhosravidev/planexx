-- ============================================================
-- ماژول محصولات و لیست‌ها - Products & Lists Module
-- MySQL Database Schema
-- به‌روزرسانی شده: ۲۲ بهمن ۱۴۰۴
-- ============================================================

-- -----------------------------------------------------------
-- جدول دسته‌بندی محصولات
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'نام دسته‌بندی',
    `slug` VARCHAR(255) NOT NULL COMMENT 'نامک (URL-friendly)',
    `parent_id` INT UNSIGNED DEFAULT NULL COMMENT 'دسته‌بندی والد',
    `icon` VARCHAR(100) DEFAULT NULL COMMENT 'آیکون دسته‌بندی',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT 'ترتیب نمایش',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_product_categories_slug` (`slug`),
    KEY `idx_product_categories_parent` (`parent_id`),
    KEY `idx_product_categories_active` (`is_active`),
    CONSTRAINT `fk_product_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='دسته‌بندی محصولات';


-- -----------------------------------------------------------
-- جدول محصولات (ساختار ساده‌شده)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(500) NOT NULL COMMENT 'عنوان محصول',
    `slug` VARCHAR(500) DEFAULT NULL COMMENT 'نامک (URL-friendly)',
    `category_id` INT UNSIGNED DEFAULT NULL COMMENT 'شناسه دسته‌بندی',
    `price` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'قیمت فروش (ریال)',
    `sale_price` BIGINT UNSIGNED DEFAULT NULL COMMENT 'قیمت با تخفیف (ریال)',
    `image_url` VARCHAR(500) DEFAULT NULL COMMENT 'تصویر اصلی محصول',
    `status` ENUM('active','inactive','draft','out_of_stock') NOT NULL DEFAULT 'draft' COMMENT 'وضعیت محصول',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'محصول ویژه',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT 'ترتیب نمایش',
    `created_by` INT UNSIGNED DEFAULT NULL COMMENT 'ایجادکننده',
    `updated_by` INT UNSIGNED DEFAULT NULL COMMENT 'آخرین ویرایش‌کننده',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_products_slug` (`slug`(191)),
    KEY `idx_products_category` (`category_id`),
    KEY `idx_products_status` (`status`),
    KEY `idx_products_price` (`price`),
    KEY `idx_products_created` (`created_at`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='محصولات - ساختار ساده';


-- -----------------------------------------------------------
-- جدول لیست‌ها (تعریف لیست‌های سفارشی)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_lists` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'نام لیست (فارسی)',
    `name_en` VARCHAR(255) NOT NULL COMMENT 'نام لیست (انگلیسی / Slug)',
    `icon` VARCHAR(100) DEFAULT 'fa-solid fa-clipboard-list' COMMENT 'آیکون لیست (Font Awesome class)',
    `color` VARCHAR(50) DEFAULT 'blue' COMMENT 'رنگ نمایشی: blue, green, purple, orange, teal, red, pink, indigo, amber, cyan, lime, rose',
    `field1_label` VARCHAR(255) DEFAULT NULL COMMENT 'لیبل فیلد اختیاری ۱',
    `field1_type` VARCHAR(50) DEFAULT NULL COMMENT 'نوع فیلد ۱: text, number, date, select, textarea',
    `field2_label` VARCHAR(255) DEFAULT NULL COMMENT 'لیبل فیلد اختیاری ۲',
    `field2_type` VARCHAR(50) DEFAULT NULL COMMENT 'نوع فیلد ۲',
    `field3_label` VARCHAR(255) DEFAULT NULL COMMENT 'لیبل فیلد اختیاری ۳',
    `field3_type` VARCHAR(50) DEFAULT NULL COMMENT 'نوع فیلد ۳',
    `field4_label` VARCHAR(255) DEFAULT NULL COMMENT 'لیبل فیلد اختیاری ۴',
    `field4_type` VARCHAR(50) DEFAULT NULL COMMENT 'نوع فیلد ۴',
    `field5_label` VARCHAR(255) DEFAULT NULL COMMENT 'لیبل فیلد اختیاری ۵',
    `field5_type` VARCHAR(50) DEFAULT NULL COMMENT 'نوع فیلد ۵',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT 'ترتیب نمایش',
    `created_by` INT UNSIGNED DEFAULT NULL COMMENT 'ایجادکننده',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_custom_lists_name_en` (`name_en`),
    KEY `idx_custom_lists_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='تعریف لیست‌های سفارشی';


-- -----------------------------------------------------------
-- جدول آیتم‌های لیست‌ها
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_list_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `list_id` INT UNSIGNED NOT NULL COMMENT 'شناسه لیست',
    `title` VARCHAR(500) NOT NULL COMMENT 'عنوان آیتم',
    `code` VARCHAR(100) DEFAULT NULL COMMENT 'کد آیتم',
    `field1_value` TEXT DEFAULT NULL COMMENT 'مقدار فیلد اختیاری ۱',
    `field2_value` TEXT DEFAULT NULL COMMENT 'مقدار فیلد اختیاری ۲',
    `field3_value` TEXT DEFAULT NULL COMMENT 'مقدار فیلد اختیاری ۳',
    `field4_value` TEXT DEFAULT NULL COMMENT 'مقدار فیلد اختیاری ۴',
    `field5_value` TEXT DEFAULT NULL COMMENT 'مقدار فیلد اختیاری ۵',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'وضعیت فعال/غیرفعال',
    `sort_order` INT NOT NULL DEFAULT 0 COMMENT 'ترتیب نمایش',
    `created_by` INT UNSIGNED DEFAULT NULL COMMENT 'ایجادکننده',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_list_items_list` (`list_id`),
    KEY `idx_list_items_code` (`code`),
    KEY `idx_list_items_active` (`list_id`, `is_active`),
    FULLTEXT KEY `ft_list_items_search` (`title`, `code`),
    CONSTRAINT `fk_list_items_list` FOREIGN KEY (`list_id`) REFERENCES `custom_lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='آیتم‌های لیست‌های سفارشی';


-- -----------------------------------------------------------
-- داده‌های اولیه: دسته‌بندی‌های پیش‌فرض
-- -----------------------------------------------------------
INSERT INTO `product_categories` (`name`, `slug`, `sort_order`) VALUES
('الکترونیکی', 'electronic', 1),
('خوراکی', 'food', 2),
('بهداشتی', 'health', 3),
('صنعتی', 'industrial', 4),
('خدماتی', 'service', 5);


-- -----------------------------------------------------------
-- داده‌های اولیه: لیست‌های نمونه
-- -----------------------------------------------------------
INSERT INTO `custom_lists` (`name`, `name_en`, `icon`, `color`, `field1_label`, `field1_type`, `field2_label`, `field2_type`, `field3_label`, `field3_type`) VALUES
('تأمین‌کنندگان', 'suppliers', 'fa-solid fa-truck', 'blue', 'شماره تماس', 'text', 'آدرس', 'text', 'امتیاز', 'number'),
('ملزومات تولید', 'production-materials', 'fa-solid fa-industry', 'green', 'واحد', 'text', 'موجودی', 'number', 'حداقل سفارش', 'number'),
('دستگاه‌ها و تجهیزات', 'equipment', 'fa-solid fa-cogs', 'purple', 'شماره سریال', 'text', 'محل نصب', 'text', 'تاریخ خرید', 'date'),
('اموال و دارایی‌ها', 'assets', 'fa-solid fa-building', 'orange', 'کد اموال', 'text', 'محل استقرار', 'text', 'تحویل‌گیرنده', 'text'),
('ابزارآلات', 'tools', 'fa-solid fa-wrench', 'teal', 'تعداد', 'number', 'محل نگهداری', 'text', NULL, NULL);
