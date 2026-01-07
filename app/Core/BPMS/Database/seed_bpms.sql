-- ====================================================================
-- BPMS Test Data Seeder
-- این فایل حاوی داده‌های تستی متنوع برای سیستم مدیریت فرایندها است
-- ====================================================================
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM `bpms_workflows`;
DELETE FROM `bpms_workflow_states`;
DELETE FROM `bpms_tasks`;
DELETE FROM `bpms_follow_ups`;
DELETE FROM `bpms_watchlist`;
-- Workflows (فرایندها)
-- ====================================================================
INSERT INTO `bpms_workflows` (`id`, `name`, `slug`, `description`, `department_id`, `owner_id`, `created_by`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'اینسپکشن', 'inception', 'فرایند بررسی و ارزیابی اولیه مشتریان جدید برای تعیین نیازمندی‌ها و ارائه پیشنهادات', NULL, 5, 5, 1, NOW(), NOW(), NULL),
(2, 'مسیریابی', 'router-campaign', 'فرایند هدایت و مسیردهی کمپین‌های تبلیغاتی و پیگیری سرنخ‌های دریافتی', NULL, 6, 5, 1, NOW(), NOW(), NULL),
(3, 'کمپین فیدبک', 'feedback-campaign', 'فرایند جمع‌آوری و پیگیری بازخورد مشتریان پس از خرید', NULL, 7, 5, 1, NOW(), NOW(), NULL),
(4, 'سبد رها شده', 'abandoned-cart', 'فرایند پیگیری و بازگرداندن مشتریانی که سبد خرید خود را رها کرده‌اند', NULL, 8, 5, 1, NOW(), NOW(), NULL),
(5, 'استخدام', 'recruitment', 'فرایند جذب و استخدام نیروی انسانی جدید', NULL, 11, 5, 0, NOW(), NOW(), NULL),
(6, 'درخواست مرخصی', 'leave-request', 'فرایند ثبت و تایید درخواست‌های مرخصی کارکنان', NULL, 11, 11, 1, NOW(), NOW(), NULL),
(7, 'خرید و تدارکات', 'procurement', 'فرایند درخواست، تایید و خرید تجهیزات و خدمات', NULL, 5, 5, 1, NOW(), NOW(), NULL);

-- Workflow States (مراحل فرایندها)
-- ====================================================================

-- States for Workflow 1: اینسپکشن
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'آزمون ورودی', 'انجام آزمون اولیه و بررسی شرایط', '#E0F2F1', 1, 0, 7, 1, NOW(), NOW(), NULL),
(2, 1, 'مشاوره خصوصی', 'جلسه مشاوره یک به یک با مشتری', '#E3F2FD', 2, 1, NULL, 1, NOW(), NOW(), NULL),
(3, 1, 'منتظر پرداخت', 'پیش‌فاکتور ارسال شده و منتظر پرداخت', '#FFF3E0', 3, 1, 8, 1, NOW(), NOW(), NULL),
(4, 1, 'پیش پرداخت شده', 'پیش‌پرداخت انجام شده و آماده شروع پروژه', '#E8F5E9', 4, 1, NULL, 1, NOW(), NOW(), NULL),
(5, 1, 'تکمیل شده', 'پروژه با موفقیت تکمیل شده', '#C8E6C9', 5, 2, NULL, 1, NOW(), NOW(), NULL),
(6, 1, 'لغو شده', 'مشتری منصرف شده یا شرایط فراهم نیست', '#FFCDD2', 6, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 2: مسیریابی
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 2, 'سرنخ', 'سرنخ جدید دریافت شده از کمپین', '#E0F2F1', 1, 0, 12, 1, NOW(), NOW(), NULL),
(8, 2, 'ارسال آفر', 'پیشنهاد اولیه به مشتری ارسال شده', '#FFF8E1', 2, 1, NULL, 1, NOW(), NOW(), NULL),
(9, 2, 'در حال مذاکره', 'در حال مذاکره و بحث درباره جزئیات', '#E3F2FD', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(10, 2, 'موفق', 'مشتری با پیشنهاد موافقت کرد', '#C8E6C9', 4, 2, NULL, 1, NOW(), NOW(), NULL),
(11, 2, 'ناموفق', 'مشتری با پیشنهاد موافقت نکرد', '#FFCDD2', 5, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 3: کمپین فیدبک
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, 3, 'سرنخ', 'مشتری شناسایی شده برای دریافت فیدبک', '#E0F2F1', 1, 0, 13, 1, NOW(), NOW(), NULL),
(13, 3, 'ارسال آفر', 'پیشنهاد یا تشویق برای فیدبک ارسال شده', '#FFF8E1', 2, 1, NULL, 1, NOW(), NOW(), NULL),
(14, 3, 'در حال مذاکره', 'در حال پیگیری برای دریافت فیدبک', '#E3F2FD', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(15, 3, 'تکمیل', 'فیدبک با موفقیت دریافت شد', '#C8E6C9', 4, 2, NULL, 1, NOW(), NOW(), NULL),
(16, 3, 'انصراف', 'مشتری تمایل به ارائه فیدبک ندارد', '#FFCDD2', 5, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 4: سبد رها شده
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 4, 'سبد پرداخت نشده', 'سبد خرید رها شده شناسایی شده', '#FFEBEE', 1, 0, 14, 1, NOW(), NOW(), NULL),
(18, 4, 'در حال پیگیری', 'تماس اول با مشتری برقرار شده', '#E3F2FD', 2, 1, NULL, 1, NOW(), NOW(), NULL),
(19, 4, 'ارسال آفر', 'تخفیف یا پیشنهاد ویژه ارسال شده', '#FFF8E1', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(20, 4, 'خرید شد', 'مشتری خرید را نهایی کرد', '#C8E6C9', 4, 2, NULL, 1, NOW(), NOW(), NULL),
(21, 4, 'رها شد', 'مشتری به پیگیری‌ها پاسخ نداد', '#FFCDD2', 5, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 5: استخدام (غیرفعال)
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(22, 5, 'دریافت رزومه', 'رزومه متقاضی دریافت شده', '#E3F2FD', 1, 0, 11, 1, NOW(), NOW(), NULL),
(23, 5, 'بررسی اولیه', 'بررسی اولیه مدارک و رزومه', '#FFF3E0', 2, 1, NULL, 1, NOW(), NOW(), NULL),
(24, 5, 'مصاحبه', 'مصاحبه حضوری یا آنلاین', '#F3E5F5', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(25, 5, 'آزمون فنی', 'انجام آزمون تخصصی', '#E8EAF6', 4, 1, NULL, 1, NOW(), NOW(), NULL),
(26, 5, 'استخدام شده', 'متقاضی استخدام شده', '#C8E6C9', 5, 2, NULL, 1, NOW(), NOW(), NULL),
(27, 5, 'رد شده', 'متقاضی رد شده', '#FFCDD2', 6, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 6: درخواست مرخصی
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(28, 6, 'درخواست ثبت شده', 'درخواست مرخصی ثبت شده', '#E3F2FD', 1, 0, NULL, 1, NOW(), NOW(), NULL),
(29, 6, 'در حال بررسی', 'در حال بررسی توسط مدیر مستقیم', '#FFF8E1', 2, 1, 11, 1, NOW(), NOW(), NULL),
(30, 6, 'تایید مدیر', 'تایید شده توسط مدیر', '#E8F5E9', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(31, 6, 'تایید نهایی', 'تایید نهایی از HR', '#C8E6C9', 4, 2, NULL, 1, NOW(), NOW(), NULL),
(32, 6, 'رد شده', 'درخواست رد شده', '#FFCDD2', 5, 3, NULL, 1, NOW(), NOW(), NULL);

-- States for Workflow 7: خرید و تدارکات
INSERT INTO `bpms_workflow_states` (`id`, `workflow_id`, `name`, `description`, `color`, `order`, `position`, `default_assignee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(33, 7, 'درخواست خرید', 'درخواست خرید ثبت شده', '#E3F2FD', 1, 0, NULL, 1, NOW(), NOW(), NULL),
(34, 7, 'بررسی فنی', 'بررسی نیازمندی‌های فنی', '#F3E5F5', 2, 1, 6, 1, NOW(), NOW(), NULL),
(35, 7, 'استعلام بها', 'دریافت قیمت از تامین‌کنندگان', '#FFF3E0', 3, 1, NULL, 1, NOW(), NOW(), NULL),
(36, 7, 'تایید مالی', 'تایید بودجه و امور مالی', '#E8F5E9', 4, 1, 8, 1, NOW(), NOW(), NULL),
(37, 7, 'خریداری شده', 'کالا یا خدمت خریداری شده', '#C8E6C9', 5, 2, NULL, 1, NOW(), NOW(), NULL),
(38, 7, 'لغو شده', 'درخواست لغو شده', '#FFCDD2', 6, 3, NULL, 1, NOW(), NOW(), NULL);


-- Tasks (کارها و وظایف)
-- ====================================================================
-- مقادیر due_date و next_follow_up_date با استفاده از توابع SQL به صورت پویا
-- در بازه زمانی ۳ روز قبل تا ۳ روز بعد از لحظه اجرای اسکریپت (NOW) تولید می‌شوند.
INSERT INTO bpms_tasks (id, slug, title, description, workflow_id, current_state_id, assignee_id, created_by, priority, estimated_hours, due_date, next_follow_up_date, completed_at, created_at, updated_at, deleted_at) VALUES
-- اینسپکشن - فوری
(1, 'INS-2024-001', 'مشاوره آقای محمدی - شرکت آلفا', 'جلسه مشاوره اولیه با مدیرعامل شرکت آلفا...', 1, 2, 5, 7, 3, 4.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- اینسپکشن - بالا
(2, 'INS-2024-002', 'آزمون ورودی سارا کریمی', 'برگزاری آزمون ورودی برای ارزیابی اولیه...', 1, 1, 7, 5, 2, 2.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- اینسپکشن - متوسط
(3, 'INS-2024-003', 'منتظر پرداخت - پروژه گاما', 'پیش‌فاکتور ارسال شده به مشتری، منتظر پرداخت...', 1, 3, 8, 5, 1, 1.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- اینسپکشن - کم
(4, 'INS-2024-004', 'پیش پرداخت پروژه دلتا', 'پیش‌پرداخت دریافت شده. آماده‌سازی مستندات...', 1, 4, 6, 5, 0, 3.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- مسیریابی - فوری
(5, 'RTE-2024-015', 'سرنخ از کمپین گوگل - شرکت بتا', 'پیگیری سرنخ با اولویت بالا دریافتی از کمپین...', 2, 8, 12, 7, 3, 2.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- مسیریابی - بالا
(6, 'RTE-2024-016', 'مذاکره با شرکت اپسیلون', 'ادامه مذاکرات قیمت و شرایط قرارداد...', 2, 9, 13, 12, 2, 3.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- مسیریابی - متوسط
(7, 'RTE-2024-017', 'سرنخ از اینستاگرام - خانم احمدی', 'سرنخ جدید از کمپین اینستاگرام...', 2, 7, 12, 6, 1, 1.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- سبد رها شده - فوری
(8, 'ABN-2024-042', 'پیگیری سبد خرید - سفارش #۱۲۳۴۵', 'سبد خرید به ارزش ۱۵ میلیون تومان رها شده...', 4, 18, 14, 8, 3, 1.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- سبد رها شده - بالا
(9, 'ABN-2024-043', 'سبد رها شده - آقای کریمی', 'سبد خرید ۸ میلیون تومانی. مشتری قبلی...', 4, 17, 14, 8, 2, 0.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- سبد رها شده - متوسط
(10, 'ABN-2024-044', 'پیگیری سبد - خانم محمدی', 'سبد ۴.۵ میلیون تومانی. مشتری جدید...', 4, 19, 14, 8, 1, 0.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- استخدام - متوسط
(11, 'RCR-2024-008', 'مصاحبه کارشناس فروش - رضا موسوی', 'مصاحبه حضوری با متقاضی استخدام...', 5, 24, 11, 11, 1, 2.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- درخواست مرخصی - بالا
(12, 'LRQ-2024-015', 'درخواست مرخصی - علی خسروی', 'درخواست مرخصی استعلاجی ۳ روزه...', 6, 29, 11, 4, 2, 0.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, -- مرخصی ممکن است تاریخ پیگیری نداشته باشد
    NULL, NOW(), NOW(), NULL),

-- درخواست مرخصی - متوسط
(13, 'LRQ-2024-016', 'مرخصی استحقاقی - مبینا رفیعیان', 'درخواست مرخصی استحقاقی ۵ روزه...', 6, 30, 11, 9, 1, 0.30,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL,
    NULL, NOW(), NOW(), NULL),

-- خرید و تدارکات - فوری
(14, 'PRC-2024-025', 'خرید سرور جدید', 'خرید سرور Dell PowerEdge برای data center...', 7, 34, 6, 5, 3, 8.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- خرید و تدارکات - بالا
(15, 'PRC-2024-026', 'استعلام بها میز و صندلی اداری', 'استعلام بها از ۳ تامین‌کننده برای خرید...', 7, 35, 16, 5, 2, 4.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- خرید و تدارکات - متوسط
(16, 'PRC-2024-027', 'تایید مالی - خرید لایسنس نرم‌افزاری', 'تایید بودجه برای خرید ۵۰ لایسنس...', 7, 36, 8, 6, 1, 2.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- کمپین فیدبک - کم
(17, 'FBK-2024-008', 'دریافت فیدبک - پروژه شرکت زتا', 'پیگیری دریافت فیدبک از مشتری پس از اتمام پروژه...', 3, 13, 13, 7, 0, 0.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- اینسپکشن - بالا
(18, 'INS-2024-005', 'مشاوره تلفنی - خانم نوری', 'مشاوره تلفنی ۳۰ دقیقه‌ای با خانم نوری...', 1, 2, 5, 7, 2, 0.50,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- مسیریابی - کم
(19, 'RTE-2024-018', 'سرنخ از لینکدین - شرکت تتا', 'سرنخ جدید از پست لینکدین...', 2, 7, 12, 6, 0, 2.00,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL),

-- سبد رها شده - کم
(20, 'ABN-2024-045', 'سبد رها شده - مهدی رضایی', 'سبد خرید ۲.۸ میلیون تومانی. محصولات...', 4, 18, 14, 8, 0, 0.30,
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    DATE_ADD(NOW(), INTERVAL (RAND() * 6 - 3) DAY),
    NULL, NOW(), NOW(), NULL);

-- Follow-ups (تاریخچه پیگیری‌ها)
-- ====================================================================
-- انواع مختلف: follow_up (0), state_transition (1), user_action (2), watcher_review (3)

-- Follow-ups for Task 1 (INS-2024-001)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(1, 1, 1, 'کار ایجاد و به مرحله آزمون ورودی منتقل شد', 7, NULL, 7, NULL, 1, '2024-12-18 09:15:00'),
(2, 1, 0, 'تماس اولیه با مشتری انجام شد. ایشان آمادگی شرکت در جلسه مشاوره را اعلام کردند. جلسه برای فردا ساعت ۱۴ تنظیم شد.', 7, NULL, NULL, NULL, NULL, '2024-12-19 11:30:00'),
(3, 1, 1, 'آزمون ورودی با موفقیت انجام شد و به مرحله مشاوره خصوصی منتقل شد', 7, 7, 5, 1, 2, '2024-12-20 14:00:00'),
(4, 1, 2, 'یادآوری: جلسه مشاوره فردا ساعت ۱۴ برگزار می‌شود. لطفاً مستندات و نمونه‌کارها را آماده کنید. مشتری خواستار دیدن پروژه‌های مشابه است.', 5, NULL, NULL, NULL, NULL, '2024-12-23 16:00:00');

-- Follow-ups for Task 2 (INS-2024-002)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(5, 2, 1, 'کار ایجاد و به مرحله آزمون ورودی منتقل شد', 5, NULL, 7, NULL, 1, '2024-12-20 11:30:00'),
(6, 2, 0, 'هماهنگی با متقاضی انجام شد. زمان آزمون: فردا ساعت ۱۰ صبح. آزمون به صورت آنلاین برگزار می‌شود.', 7, NULL, NULL, NULL, NULL, '2024-12-24 13:45:00');

-- Follow-ups for Task 3 (INS-2024-003)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(7, 3, 1, 'کار ایجاد و به مرحله آزمون ورودی منتقل شد', 5, NULL, 7, NULL, 1, '2024-12-16 14:20:00'),
(8, 3, 1, 'مشاوره انجام و پیش‌فاکتور ارسال شد. به مرحله منتظر پرداخت منتقل شد', 7, 7, 8, 1, 3, '2024-12-18 16:30:00'),
(9, 3, 0, 'پیگیری تلفنی اول انجام شد. مشتری در جلسه بود، قرار شد فردا دوباره تماس بگیریم.', 8, NULL, NULL, NULL, NULL, '2024-12-21 10:15:00'),
(10, 3, 0, 'پیگیری تلفنی دوم. مشتری اعلام کرد تا آخر هفته بودجه تامین می‌شود و پرداخت انجام می‌دهد.', 8, NULL, NULL, NULL, NULL, '2024-12-23 14:20:00');

-- Follow-ups for Task 5 (RTE-2024-015)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(11, 5, 1, 'سرنخ جدید دریافت شد', 7, NULL, 12, NULL, 7, '2024-12-13 13:25:00'),
(12, 5, 0, 'تماس اولیه با مشتری. علاقه‌مند به خدمات طراحی وب‌سایت و اپلیکیشن موبایل. درخواست ارسال نمونه‌کار و قیمت.', 12, NULL, NULL, NULL, NULL, '2024-12-14 10:30:00'),
(13, 5, 1, 'نمونه‌کارها ارسال شد و به مرحله ارسال آفر منتقل شد', 12, NULL, NULL, 7, 8, '2024-12-15 16:00:00'),
(14, 5, 0, 'پیشنهاد قیمت ارسال شد. مبلغ: ۱۲۰ میلیون تومان. زمان تحویل: ۳ ماه. مشتری تا پایان هفته بررسی می‌کند.', 12, NULL, NULL, NULL, NULL, '2024-12-16 11:45:00'),
(15, 5, 0, 'تماس پیگیری. مشتری چند سوال فنی پرسید که پاسخ داده شد. قرار است با شریک تجاری خود مشورت کند.', 12, NULL, NULL, NULL, NULL, '2024-12-19 09:20:00'),
(16, 5, 2, 'یادآوری مهم: مشتری امروز تماس می‌گیرد برای نهایی کردن. آماده باش!', 7, NULL, NULL, NULL, NULL, '2024-12-24 08:00:00');

-- Follow-ups for Task 6 (RTE-2024-016)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(17, 6, 1, 'سرنخ جدید دریافت شد', 12, NULL, 12, NULL, 7, '2024-12-03 16:40:00'),
(18, 6, 1, 'پس از بررسی اولیه، آفر ارسال شد', 12, NULL, NULL, 7, 8, '2024-12-05 14:20:00'),
(19, 6, 1, 'مشتری پیشنهاد قیمت داد، وارد مرحله مذاکره شدیم', 12, 12, 13, 8, 9, '2024-12-10 10:15:00'),
(20, 6, 0, 'جلسه اول مذاکره. مشتری درخواست تخفیف ۱۵٪ کرد. باید با مدیر مشورت کنیم.', 13, NULL, NULL, NULL, NULL, '2024-12-12 11:30:00'),
(21, 6, 0, 'جلسه دوم. تخفیف ۱۰٪ پیشنهاد دادیم و مشتری قبول کرد. در حال آماده‌سازی قرارداد هستیم.', 13, NULL, NULL, NULL, NULL, '2024-12-18 15:45:00'),
(22, 6, 2, 'جلسه نهایی فردا ساعت ۱۰. نیاز به آماده‌سازی پاورپوینت و پیش‌نویس قرارداد.', 12, NULL, NULL, NULL, NULL, '2024-12-24 17:00:00');

-- Follow-ups for Task 8 (ABN-2024-042)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(23, 8, 1, 'سبد رها شده شناسایی شد', 8, NULL, 14, NULL, 17, '2024-12-23 19:30:00'),
(24, 8, 1, 'تماس اول با مشتری برقرار شد', 14, NULL, NULL, 17, 18, '2024-12-24 10:15:00'),
(25, 8, 0, 'مشتری اعلام کرد هزینه بالاست. تخفیف ۱۰٪ پیشنهاد دادیم (۱.۵ میلیون تومان). مشتری فکر می‌کند.', 14, NULL, NULL, NULL, NULL, '2024-12-24 10:20:00');

-- Follow-ups for Task 12 (LRQ-2024-015)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(26, 12, 1, 'درخواست مرخصی ثبت شد', 4, NULL, NULL, NULL, 28, '2024-12-23 08:30:00'),
(27, 12, 1, 'درخواست به مدیر ارجاع شد', 4, NULL, 11, 28, 29, '2024-12-23 08:35:00'),
(28, 12, 0, 'در حال بررسی وضعیت کاری و امکان جایگزینی برای این بازه زمانی', 11, NULL, NULL, NULL, NULL, '2024-12-24 09:15:00');

-- Follow-ups for Task 14 (PRC-2024-025)
INSERT INTO `bpms_follow_ups` (`id`, `task_id`, `type`, `content`, `created_by`, `previous_assignee_id`, `new_assignee_id`, `previous_state_id`, `new_state_id`, `created_at`) VALUES
(29, 14, 1, 'درخواست خرید ثبت شد', 5, NULL, NULL, NULL, 33, '2024-12-22 10:20:00'),
(30, 14, 1, 'به بخش فنی ارجاع شد برای بررسی مشخصات', 5, NULL, 6, 33, 34, '2024-12-22 10:25:00'),
(31, 14, 0, 'مشخصات فنی بررسی شد. سرور Dell PowerEdge R750 پیشنهاد می‌شود: 2x Intel Xeon Gold, 256GB RAM, 8TB Storage. قیمت تخمینی: ۲۴۵ میلیون تومان.', 6, NULL, NULL, NULL, NULL, '2024-12-23 14:30:00'),
(32, 14, 2, 'فوری: نیاز به تایید سریع برای ثبت سفارش. تامین‌کننده تخفیف ۵٪ برای خرید این هفته می‌دهد.', 5, NULL, NULL, NULL, NULL, '2024-12-24 08:00:00');


-- Watchlist (ناظران)
-- ====================================================================
-- اضافه کردن ناظران برای task های مهم

INSERT INTO `bpms_watchlist` (`id`, `task_id`, `watcher_id`, `watch_status`, `watch_reason`, `comment`, `created_at`, `updated_at`) VALUES
-- Task 1 - مشاوره آقای محمدی (مدیر فروش ناظر است)
(1, 1, 7, 0, 'نظارت بر پیشرفت کار و اطمینان از انجام به موقع مشاوره', NULL, '2024-12-18 09:20:00', NOW()),
(2, 1, 8, 0, 'نظارت مالی بر پیش‌فاکتور و قرارداد', NULL, '2024-12-20 14:30:00', NOW()),

-- Task 5 - سرنخ از کمپین گوگل (مدیر عملیات و مدیر فروش ناظر)
(3, 5, 7, 0, 'نظارت بر روند فروش و مذاکره با مشتری مهم', NULL, '2024-12-14 10:00:00', NOW()),
(4, 5, 5, 0, 'پروژه بزرگ - نیاز به نظارت مدیریتی', NULL, '2024-12-15 16:30:00', NOW()),

-- Task 6 - مذاکره با شرکت اپسیلون
(5, 6, 7, 0, 'پیگیری مراحل مذاکره و پشتیبانی تیم', NULL, '2024-12-10 10:30:00', NOW()),

-- Task 8 - سبد رها شده مهم (مدیر فروش ناظر)
(6, 8, 8, 0, 'سبد با ارزش بالا - نظارت مالی', NULL, '2024-12-23 20:00:00', NOW()),

-- Task 12 - درخواست مرخصی (مدیر HR و مدیر مستقیم)
(7, 12, 11, 0, 'بررسی درخواست مرخصی و تایید نهایی', NULL, '2024-12-23 08:40:00', NOW()),
(8, 12, 5, 0, 'نظارت بر جایگزینی و مدیریت کارها در غیاب کارمند', NULL, '2024-12-23 09:00:00', NOW()),

-- Task 14 - خرید سرور (مدیرعامل و مدیر مالی ناظر)
(9, 14, 5, 0, 'تایید نهایی خرید با مبلغ بالا', NULL, '2024-12-22 10:30:00', NOW()),
(10, 14, 8, 0, 'نظارت مالی و تایید بودجه', NULL, '2024-12-22 11:00:00', NOW()),

-- Task 15 - استعلام بها میز و صندلی
(11, 15, 5, 0, 'نظارت بر خرید تجهیزات اداری', NULL, '2024-12-18 12:00:00', NOW());

-- ====================================================================
-- پایان فایل seed
-- ====================================================================
SET FOREIGN_KEY_CHECKS = 1;
