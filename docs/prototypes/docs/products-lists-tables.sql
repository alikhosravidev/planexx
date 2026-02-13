-- ============================================================
-- ماژول محصولات و لیست‌ها - نسخه حرفه‌ای (Refactored)
-- معماری: EAV برای لیست‌ها | Many-to-Many برای دسته‌بندی‌ها
-- ============================================================

-- -----------------------------------------------------------
-- 1. جدول دسته‌بندی محصولات (Standard Adjacency List)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'نام دسته‌بندی',
    `slug` VARCHAR(255) NOT NULL COMMENT 'نامک یکتا برای URL',
    `parent_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'والد برای ساختار درختی',
    `icon_class` VARCHAR(50) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft Delete',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_categories_slug` (`slug`),
    KEY `idx_categories_parent` (`parent_id`),
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. جدول اصلی محصولات (Normalized)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `price` BIGINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'قیمت (ریال)',
    `sale_price` BIGINT UNSIGNED DEFAULT NULL,
    -- وضعیت به جای ENUM از TINYINT استفاده می‌کند (1: فعال، 2: پیش‌نویس، 3: ناموجود)
    -- این کار پرفورمنس را بالا برده و تغییرات آینده را راحت‌تر می‌کند.
    `status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `updated_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_products_slug` (`slug`),
    UNIQUE KEY `uk_products_sku` (`sku`),
    KEY `idx_products_price` (`price`),
    KEY `idx_products_status` (`status`),
    CONSTRAINT `fk_products_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_products_updater` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 3. جدول میانی محصول-دسته‌بندی (Many-to-Many Pivot)
-- * راهکار مشکل شماره ۲: یک محصول می‌تواند در چند دسته باشد.
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `category_product` (
    `product_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`product_id`, `category_id`),
    KEY `idx_cat_prod_category` (`category_id`),
    CONSTRAINT `fk_cp_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cp_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- بخش لیست‌های سفارشی (The EAV Transformation)
-- ============================================================

-- -----------------------------------------------------------
-- 5. تعریف لیست‌ها (Entity)
-- مثل: "لیست اموال"، "لیست قطعات یدکی"
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_lists` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `icon_class` VARCHAR(50) DEFAULT 'fa-list',
    `color` VARCHAR(7) DEFAULT '#000000',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_custom_lists_slug` (`slug`),
    CONSTRAINT `fk_custom_lists_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 6. تعریف ویژگی‌های هر لیست (Attribute Definitions)
-- * راهکار مشکل شماره ۱: تعریف داینامیک فیلدها
-- اینجا مشخص می‌کنیم هر لیست چه ستون‌هایی دارد (بدون محدودیت تعداد)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_list_attributes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `list_id` BIGINT UNSIGNED NOT NULL,
    `label` VARCHAR(255) NOT NULL COMMENT 'عنوان فیلد. مثلا: تاریخ انقضا',
    `key_name` VARCHAR(100) NOT NULL COMMENT 'شناسه فنی فیلد. مثلا: expiration_date',
    `data_type` ENUM('text', 'number', 'date', 'boolean', 'select') NOT NULL DEFAULT 'text',
    `is_required` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_list_attr_key` (`list_id`, `key_name`),
    CONSTRAINT `fk_attributes_list` FOREIGN KEY (`list_id`) REFERENCES `custom_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 7. آیتم‌های داخل لیست (Instances)
-- هر رکورد در این جدول، یک سطر از لیست است
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_list_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `list_id` BIGINT UNSIGNED NOT NULL,
    `reference_code` VARCHAR(100) DEFAULT NULL COMMENT 'کد پیگیری یا سریال آیتم',
    `created_by` BIGINT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_list_items_list` (`list_id`),
    CONSTRAINT `fk_items_list` FOREIGN KEY (`list_id`) REFERENCES `custom_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 8. مقادیر ویژگی‌ها (Values)
-- * قلب تپنده EAV: مقادیر اینجا ذخیره می‌شوند
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_list_values` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `item_id` BIGINT UNSIGNED NOT NULL COMMENT 'مربوط به کدام آیتم؟',
    `attribute_id` BIGINT UNSIGNED NOT NULL COMMENT 'مربوط به کدام ویژگی؟',

    -- ذخیره‌سازی مقادیر:
    -- برای بهینگی می‌توانیم ستون‌های جداگانه برای انواع داده داشته باشیم
    -- تا بتوانیم روی اعداد Sort و Filter درست انجام دهیم.
    `value_text` TEXT DEFAULT NULL,
    `value_number` DECIMAL(19, 4) DEFAULT NULL,
    `value_date` DATETIME DEFAULT NULL,
    `value_boolean` TINYINT(1) DEFAULT NULL,

    PRIMARY KEY (`id`),
    -- ایندکس ترکیبی برای جستجوی سریع یک ویژگی خاص در یک آیتم
    UNIQUE KEY `uk_item_attribute` (`item_id`, `attribute_id`),

    -- ایندکس‌های جداگانه برای سرعت فیلترینگ روی مقادیر
    KEY `idx_values_number` (`attribute_id`, `value_number`),
    KEY `idx_values_date` (`attribute_id`, `value_date`),

    CONSTRAINT `fk_values_item` FOREIGN KEY (`item_id`) REFERENCES `custom_list_items` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_values_attr` FOREIGN KEY (`attribute_id`) REFERENCES `custom_list_attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
