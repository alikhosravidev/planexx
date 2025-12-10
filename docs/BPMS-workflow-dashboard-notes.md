# BPMS Workflow Dashboard – Key Architecture & Standards

## 1. استفاده از زیرساخت Registry / Registrar

- **زیرساخت مشترک**
  - استفاده از `BaseRegistryManager` و Registryهای سراسری:
    - `stat` → `StatManager`
    - `quick-access` → `QuickAccessManager`
    - `distribution` → `DistributionManager`
    - `menu` → `MenuManager`
  - رجیسترارها (Registrar) فقط مسئول **تعریف آیتم‌ها** هستند و ساختار داده و تبدیل نهایی را به Manager می‌سپارند.

- **کلیدهای Registry اختصاصی BPMS**
  - `bpms.dashboard.stats`
  - `bpms.dashboard.quick-access`
  - `bpms.dashboard.top-workflows`
  - `bpms.dashboard.task-distribution`
  - این نام‌گذاری‌ها با الگوی موجود برای داشبورد اصلی و Organization هماهنگ است (namespace + context + بخش).

- **محل پیاده‌سازی Registrarها برای BPMS**
  - همگی در ماژول BPMS و نه در لایه‌ی سراسری:
    - `App\Core\BPMS\Registrars\BPMSStatsRegistrar`
    - `App\Core\BPMS\Registrars\BPMSQuickAccessRegistrar`
    - `App\Core\BPMS\Registrars\BPMSTopWorkflowsRegistrar`
    - `App\Core\BPMS\Registrars\BPMSDistributionRegistrar`
    - `App\Core\BPMS\Registrars\BPMSMenuRegistrar`
  - رجیستر شدن همه‌ی این‌ها در خود ماژول و از طریق `BPMSServiceProvider`.

- **رجیستر Registrarها در Service Provider**
  - در `BPMSServiceProvider::boot()`:
    - `app('stat')->registerBy(BPMSStatsRegistrar::class);`
    - `app('stat')->registerBy(BPMSTopWorkflowsRegistrar::class);`
    - `app('quick-access')->registerBy(BPMSQuickAccessRegistrar::class);`
    - `app('distribution')->registerBy(BPMSDistributionRegistrar::class);`
    - `app('menu')->registerBy(BPMSMenuRegistrar::class);`

## 2. کنترلر و ویوی داشبورد BPMS

- **کنترلر**
  - فایل: `App\Core\BPMS\Http\Controllers\Web\BPMSDashboardController`
  - الگو گرفته از `OrganizationDashboardController`:
    - تزریق:
      - `StatManager`
      - `QuickAccessManager`
      - `DistributionManager`
    - مقداردهی:
      - `pageTitle = 'مدیریت وظایف'`
      - `currentPage = 'bpms-dashboard'`
      - `breadcrumbs` مشابه داشبورد اصلی (`داشبورد` → `مدیریت وظایف`).
    - داده‌ها:
      - `$stats = $statManager->getTransformed('bpms.dashboard.stats');`
      - `$quickAccessModules = $quickAccessManager->getTransformed('bpms.dashboard.quick-access');`
      - `$topWorkflows = $statManager->getTransformed('bpms.dashboard.top-workflows');`
      - `$taskDistribution = $distributionManager->getTransformed('bpms.dashboard.task-distribution');`

- **ویو داشبورد**
  - فایل: `app/Core/BPMS/Resources/views/dashboard/index.blade.php`
  - الگو از `Organization::dashboard.index` با استفاده از کامپوننت‌های سراسری:
    - `<x-layouts.app>`
    - `<x-dashboard.sidebar name="bpms.sidebar" ... />`
    - `<x-dashboard.header :title="$title" :breadcrumbs="$breadcrumbs" />`
    - `<x-dashboard.stats :items="$stats" />`
    - `<x-dashboard.quick-access :modules="$quickAccessModules" />`
    - ساختار گرید پایین صفحه:
      - `<x-BPMS::top-workflows :items="$topWorkflows" class="lg:col-span-2" />`
      - `<x-dashboard.distribution :items="$taskDistribution" title="وضعیت کارها" />`
  - **قاعده‌ی اصلی**: هرجا اختلافی بین پروتوتایپ و کامپوننت‌های موجود بود، **کامپوننت‌های موجود اولویت دارند**.

## 3. کامپوننت‌های Blade و تفکیک ماژولی

- **کامپوننت‌های سراسری داشبورد**
  - مسیر: `resources/views/components/dashboard/`
  - کامپوننت‌های استفاده‌شده:
    - `stats.blade.php` → `<x-dashboard.stats>`
    - `quick-access.blade.php` → `<x-dashboard.quick-access>`
    - `distribution.blade.php` → `<x-dashboard.distribution>`
    - `sidebar.blade.php` → `<x-dashboard.sidebar>`
    - `header.blade.php` → `<x-dashboard.header>`

- **کامپوننت اختصاصی ماژول BPMS**
  - مسیر: `app/Core/BPMS/Resources/views/components/top-workflows.blade.php`
  - استفاده به‌صورت: `<x-BPMS::top-workflows ... />`
  - نکته‌ی مهم:
    - کامپوننت‌های تخصصی هر ماژول (مثل BPMS) باید در همان ماژول تعریف شوند (مشابه `x-Organization::access-modal`).
    - View namespace ماژول از طریق `BPMSServiceProvider` با `loadViewsFrom(..., 'BPMS')` ثبت می‌شود.

## 4. منوها و Sidebar

- **ساخت منوها**
  - استفاده از زیرساخت `MenuManager` و `MenuRegistrar`:
    - برای داشبورد اصلی: `DashboardMenuRegistrar`
    - برای Organization: `OrganizationMenuRegistrar`
    - برای BPMS: `BPMSMenuRegistrar`

- **BPMSMenuRegistrar**
  - ثبت در دو منو:
    - `dashboard.sidebar`:
      - آیتم «مدیریت وظایف» با route `web.bpms.dashboard` و آیکون `fa-list-check` (ورود به ماژول).
    - `bpms.sidebar` (منوی داخلی ماژول)، مطابق پروتوتایپ:
      - «داشبورد ماژول» → `web.bpms.dashboard`
      - «مدیریت فرایندها» → فعلاً `url('#')`
      - «کارهای جاری» → فعلاً `url('#')`
      - «گزارش‌گیری» → فعلاً `url('#')`
  - هماهنگی با کامپوننت `x-dashboard.sidebar` که با `app('menu')->getTransformed($name)` آیتم‌ها را دریافت می‌کند و `currentPage` را با `id` مقایسه می‌کند.

## 5. الگوی روت‌ها و کنترلر وب BPMS

- **Route وب**
  - فایل: `app/Core/BPMS/Routes/web.php`
  - الگو مطابق `routes/web.php` اصلی:
    - گروه با `web` + `auth`
    - prefix ماژول: `bpms`
    - name prefix: `web.bpms.*`
    - روت داشبورد:
      - `Route::get('dashboard', [BPMSDashboardController::class, 'index'])->name('dashboard');`

- **Service Provider ماژول**
  - `BPMSServiceProvider`:
    - `loadRoutesFrom('BPMS/Routes/V1/admin.php');`
    - `loadRoutesFrom('BPMS/Routes/web.php');`
    - `loadViewsFrom('BPMS/Resources/views', 'BPMS');`
    - `loadMigrationsFrom('BPMS/Database/Migrations');`
    - رجیستر Registrarهای BPMS برای stat/quick-access/distribution/menu.

## 6. بهینه‌سازی کوئری‌ها در Registrarها (CASE WHEN)

- **الگوی مرجع**
  - `UserQueryImplementation::getActiveUserStats()`
    - استفاده از یک کوئری با چند `selectRaw` و `CASE WHEN` برای استخراج چند آمار در یک ضربه به دیتابیس.

- **BPMSStatsRegistrar::getTaskStats()**
  - به‌جای چندین clone روی query، از یک کوئری aggregate استفاده می‌شود:
    - `COUNT(*) as total`
    - `SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed`
    - `SUM(CASE WHEN completed_at IS NULL AND due_date IS NOT NULL AND due_date < NOW() THEN 1 ELSE 0 END) as overdue`
  - سپس در PHP:
    - `in_progress = max(0, total - completed)`

- **BPMSDistributionRegistrar::getTaskDistributionStats()**
  - همان کوئری aggregate (با CASE WHEN) و محاسبه‌ی:
    - `total`, `completed`, `overdue`
    - `in_progress = total - completed`
    - `initial = in_progress - overdue`
    - `failed` فعلاً ۰ (جای توسعه‌ی بعدی)
  - در `register()` بدون `foreach`، چهار segment مجزا ساخته می‌شود، هرکدام با:
    - label، value → `"%d کار (%d%%)"`، percent، color، order.

## 7. اصول و استانداردهای کلیدی که رعایت شد

- **تفکیک ماژولی کامل**
  - همه‌ی کلاس‌ها، کنترلرها، ویوها و Registrarهای مربوط به BPMS فقط در `app/Core/BPMS` قرار گرفتند.
  - کامپوننت‌های اختصاصی ماژول (`x-BPMS::...`) داخل `Resources/views/components` همان ماژول.

- **استفاده‌ی سازگار از زیرساخت موجود**
  - Registry/Registrar برای Stats, Quick Access, Distribution, Menu.
  - احترام به الگوهای موجود در:
    - `DashboardController`
    - `OrganizationDashboardController`
    - `Organization*Registrar`ها.

- **اولویت با Blade Componentهای موجود**
  - پروتوتایپ HTML/Vue فقط منبع الهام بود؛ در نهایت:
    - `x-dashboard.stats`, `x-dashboard.quick-access`, `x-dashboard.distribution`, `x-dashboard.sidebar`, `x-dashboard.header` معیار اصلی هستند.

- **کوئری‌های بهینه و متمرکز**
  - استخراج منطق پرس‌وجو در متدهای `private` داخل Registrar (مثلاً `getTaskStats`، `getTaskDistributionStats`).
  - استفاده از **یک کوئری** aggregate با `CASE WHEN` برای محاسبه چند آمار.

- **خوانایی Registrarها**
  - حذف `foreach`های غیرضروری در ساخت segments و استفاده از الگوی صریح (مثل BPMSStatsRegistrar).
  - نام‌گذاری شفاف برای کلیدهای Registry و آیتم‌های منو (`bpms-dashboard`, `bpms-workflows`, `bpms-tasks-active`, ...).

- **همسان‌سازی تجربه کاربری با سایر ماژول‌ها**
  - ساختار صفحه (sidebar + header + content) کاملاً مطابق داشبورد سازمانی.
  - استفاده از همان طراحی و کلاس‌های Tailwind در حد ممکن، بدون شکستن کامپوننت‌های مشترک.
