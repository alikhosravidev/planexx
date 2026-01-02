<x-panel::layouts.dashboard title="تست کامپوننت‌ها" current-page="dashboard">
    <div class="space-y-8">

        <section>
            <h2 class="text-xl font-bold mb-4">کارت‌های آماری</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-panel::dashboard.stat-card
                    title="تعداد کاربران"
                    value="247"
                    change="+12%"
                    changeType="increase"
                    icon="fa-solid fa-users"
                    color="blue"
                />
                <x-panel::dashboard.stat-card
                    title="کارمندان فعال"
                    value="189"
                    change="+8%"
                    changeType="increase"
                    icon="fa-solid fa-user-tie"
                    color="green"
                />
                <x-panel::dashboard.stat-card
                    title="دپارتمان‌ها"
                    value="24"
                    change="+2"
                    changeType="increase"
                    icon="fa-solid fa-sitemap"
                    color="purple"
                />
                <x-panel::dashboard.stat-card
                    title="موقعیت‌های شغلی"
                    value="38"
                    change="+5"
                    changeType="increase"
                    icon="fa-solid fa-briefcase"
                    color="orange"
                />
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">صفحه‌بندی (Pagination)</h2>
            <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                <x-panel::ui.pagination
                    :from="1"
                    :to="10"
                    :total="42"
                    label="رکورد"
                    :current="1"
                    prevUrl="#"
                    nextUrl="#"
                    :hasPrev="false"
                    :hasNext="true"
                />
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">دسترسی سریع</h2>
            <x-panel::dashboard.quick-access :modules="[
                ['title' => 'ساختار سازمانی', 'icon' => 'fa-solid fa-sitemap', 'color' => 'blue', 'url' => '#', 'enabled' => true],
                ['title' => 'مدیریت اسناد و فایل‌ها', 'icon' => 'fa-solid fa-folder-open', 'color' => 'amber', 'url' => '#', 'enabled' => true],
                ['title' => 'مدیریت وظایف', 'icon' => 'fa-solid fa-list-check', 'color' => 'indigo', 'url' => '#', 'enabled' => true],
                ['title' => 'پایگاه تجربه سازمانی', 'icon' => 'fa-solid fa-book', 'color' => 'teal', 'url' => '#', 'enabled' => true],
                ['title' => 'مالی و وصول مطالبات', 'icon' => 'fa-solid fa-coins', 'color' => 'green', 'url' => '#', 'enabled' => true],
                ['title' => 'CRM', 'icon' => 'fa-solid fa-users-line', 'color' => 'purple', 'url' => '#', 'enabled' => true],
                ['title' => 'به زودی', 'icon' => 'fa-solid fa-lock', 'color' => 'gray', 'url' => '#', 'enabled' => false],
            ]"/>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">آمار کوچک (Mini Stats)</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <x-panel::ui.stat-mini icon="fa-file" iconColor="blue" value="۱,۲۴۸" label="فایل"/>
                <x-panel::ui.stat-mini icon="fa-folder" iconColor="amber" value="۸۶" label="پوشه"/>
                <x-panel::ui.stat-mini icon="fa-star" iconColor="yellow" value="۴۵" label="پسندیده"/>
                <x-panel::ui.stat-mini icon="fa-hard-drive" iconColor="green" value="۱۲.۵" label="گیگابایت"/>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">دکمه‌ها</h2>
            <div class="flex flex-wrap gap-4">
                <x-panel::ui.button variant="primary" icon="fa-save">
                    ذخیره
                </x-panel::ui.button>
                <x-panel::ui.button variant="secondary" icon="fa-times">
                    انصراف
                </x-panel::ui.button>
                <x-panel::ui.button variant="danger" icon="fa-trash">
                    حذف
                </x-panel::ui.button>
                <x-panel::ui.button variant="outline" icon="fa-download">
                    دانلود
                </x-panel::ui.button>
                <x-panel::ui.button variant="primary" :loading="true">
                    در حال بارگذاری...
                </x-panel::ui.button>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">بج‌ها</h2>
            <div class="flex flex-wrap gap-2">
                <x-panel::ui.badge variant="default">پیش‌فرض</x-ui.badge>
                <x-panel::ui.badge variant="success">موفق</x-ui.badge>
                <x-panel::ui.badge variant="warning">هشدار</x-ui.badge>
                <x-panel::ui.badge variant="danger">خطا</x-ui.badge>
                <x-panel::ui.badge variant="info">اطلاعات</x-ui.badge>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">آواتار</h2>
            <div class="flex flex-wrap gap-4 items-end">
                <x-panel::ui.avatar name="علی احمدی" size="sm"/>
                <x-panel::ui.avatar name="محمد رضایی" size="md"/>
                <x-panel::ui.avatar name="فاطمه محمدی" size="lg"/>
                <x-panel::ui.avatar name="سارا قاسمی" size="xl"/>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">Breadcrumb</h2>
            <x-panel::ui.breadcrumb :items="[
                ['label' => 'خانه', 'url' => '#'],
                ['label' => 'داشبورد', 'url' => '#'],
                ['label' => 'تست کامپوننت‌ها']
            ]"/>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">نوار ذخیره‌سازی</h2>
            <x-panel::ui.storage-bar :used="12.5" :total="50" unit="GB"/>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">ورودی OTP</h2>
            <x-panel::ui.otp-input :length="4"/>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">کارت‌ها</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-panel::ui.card title="کارت با عنوان" subtitle="این یک کارت نمونه است">
                    محتوای کارت در اینجا قرار می‌گیرد.
                </x-panel::ui.card>
                <x-panel::ui.card>
                    کارت بدون عنوان
                </x-panel::ui.card>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">کارت تجربه</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-panel::ui.experience-card
                    title="بهینه‌سازی فرآیند قراردادهای مالی"
                    summary="با استفاده از اتوماسیون در فرآیند قراردادها، زمان پردازش را ۴۰٪ کاهش دادیم"
                    department="مالی"
                    departmentColor="green"
                    author="احمد باقری"
                    authorPosition="مدیر مالی"
                    date="۱۴۰۳/۰۹/۰۵"
                    :views="145"
                    :rating="4.8"
                    :attachments="5"
                    :tags="['اتوماسیون', 'قرارداد', 'مالی']"
                    url="#"
                />
                <x-panel::ui.experience-card
                    title="کاهش زمان تولید محصول X"
                    summary="با تغییر چیدمان خط تولید و بهینه‌سازی مراحل، بهره‌وری ۳۷٪ افزایش یافت"
                    department="تولید"
                    departmentColor="slate"
                    author="رضا صانعی"
                    authorPosition="مدیر تولید"
                    date="۱۴۰۳/۰۹/۰۴"
                    :views="203"
                    :rating="4.9"
                    :attachments="3"
                    :tags="['تولید', 'بهره‌وری']"
                    url="#"
                />
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">آیتم پوشه</h2>
            <div class="bg-white p-4 rounded-lg">
                <x-panel::ui.folder-item
                    id="strategic"
                    label="اسناد استراتژیک"
                    icon="fa-landmark"
                    color="purple"
                    :children="[
                        ['label' => 'منابع انسانی', 'icon' => 'fa-users', 'url' => '#'],
                        ['label' => 'حقوقی', 'icon' => 'fa-scale-balanced', 'url' => '#'],
                        ['label' => 'مدیریتی', 'icon' => 'fa-briefcase', 'url' => '#'],
                    ]"
                />
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">نوار فیلتر</h2>
            <form>
                <x-panel::ui.filter-bar :filters="[
                    [
                        'type' => 'text',
                        'name' => 'search',
                        'label' => 'جستجو',
                        'placeholder' => 'نام، موبایل یا ایمیل'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'department',
                        'label' => 'دپارتمان',
                        'options' => [
                            '' => 'همه دپارتمان‌ها',
                            'sales' => 'فروش',
                            'tech' => 'فنی',
                            'hr' => 'منابع انسانی'
                        ]
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'وضعیت',
                        'options' => [
                            '' => 'همه',
                            'active' => 'فعال',
                            'inactive' => 'غیرفعال'
                        ]
                    ]
                ]"/>
            </form>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">جدول داده</h2>
            <x-panel::ui.data-table
                :headers="['نام', 'موبایل', 'ایمیل', 'وضعیت']"
                :rows="[
                    [
                        '<div class=\'flex items-center gap-2\'><x-panel::ui.avatar name=\'علی احمدی\' size=\'sm\' /><span>علی احمدی</span></div>',
                        '09121234567',
                        'ali@example.com',
                        '<span class=\'inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium\'>فعال</span>'
                    ],
                    [
                        '<div class=\'flex items-center gap-2\'><x-panel::ui.avatar name=\'فاطمه محمدی\' size=\'sm\' /><span>فاطمه محمدی</span></div>',
                        '09129876543',
                        'fateme@example.com',
                        '<span class=\'inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium\'>فعال</span>'
                    ]
                ]"
                actionsHeader="عملیات"
                :actions="[
                    '<div class=\'flex items-center justify-center gap-2\'>
                        <a href=\'#\' class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200\' title=\'مشاهده\'>
                            <i class=\'fa-solid fa-eye\'></i>
                        </a>
                        <a href=\'#\' class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200\' title=\'ویرایش\'>
                            <i class=\'fa-solid fa-pen\'></i>
                        </a>
                        <button class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200\' title=\'حذف\'>
                            <i class=\'fa-solid fa-trash\'></i>
                        </button>
                    </div>',
                    '<div class=\'flex items-center justify-center gap-2\'>
                        <a href=\'#\' class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200\' title=\'مشاهده\'>
                            <i class=\'fa-solid fa-eye\'></i>
                        </a>
                        <a href=\'#\' class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200\' title=\'ویرایش\'>
                            <i class=\'fa-solid fa-pen\'></i>
                        </a>
                        <button class=\'w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200\' title=\'حذف\'>
                            <i class=\'fa-solid fa-trash\'></i>
                        </button>
                    </div>'
                ]"
            />
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">جعبه جستجو (App)</h2>
            <div class="max-w-md">
                <x-panel::app.search-box placeholder="جستجو در تجربیات..." :withVoice="true"/>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">Tom Select - سلکت پیشرفته</h2>
            <div class="bg-white border border-border-light rounded-2xl p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت ساده</h3>
                        <x-panel::forms.tom-select
                            name="demo_simple"
                            label="وضعیت"
                            :options="['active' => 'فعال', 'inactive' => 'غیرفعال', 'pending' => 'در انتظار']"
                            value="active"
                        />
                    </div>

                    <div>
                        <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت بدون لیبل</h3>
                        <div class="ts-standalone">
                            <select data-tom-select data-placeholder="انتخاب دسته‌بندی...">
                                <option value="">انتخاب دسته‌بندی...</option>
                                <option value="tech">فناوری</option>
                                <option value="sales">فروش</option>
                                <option value="hr">منابع انسانی</option>
                                <option value="finance">مالی</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت چندگانه</h3>
                        <x-panel::forms.tom-select
                            name="demo_multiple"
                            label="مهارت‌ها"
                            :options="['php' => 'PHP', 'js' => 'JavaScript', 'python' => 'Python', 'go' => 'Go', 'rust' => 'Rust']"
                            :value="['php', 'js']"
                            :multiple="true"
                        />
                    </div>

                    <div>
                        <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت با تگ (ایجاد آیتم جدید)</h3>
                        <x-panel::forms.tom-select-tags
                            name="demo_tags"
                            label="برچسب‌ها"
                            :options="['important' => 'مهم', 'urgent' => 'فوری']"
                            :value="['important']"
                        />
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت با جستجوی AJAX (کاربران)</h3>
                    <x-panel::forms.tom-select-ajax
                        name="demo_ajax_user"
                        label="انتخاب کاربر"
                        placeholder="جستجوی کاربر..."
                        url="{{ route('api.v1.admin.org.users.keyValList', ['field' => 'full_name']) }}"
                        :preload="true"
                    />
                    <br />
                    <x-panel::forms.tom-select-ajax
                        name="test"
                        label="دپارتمان"
                        placeholder="جستجوی دپارتمان..."
                        url="{{ route('api.v1.admin.org.departments.index', ['per_page' => 100, 'field' => 'name', 'filter' => ['parent_id' => ''], 'includes' => 'children']) }}"
                        template="departments"
                    />
                </div>

                <div>
                    <h3 class="text-base font-semibold mb-3 text-text-primary">سلکت سایز کوچک</h3>
                    <div class="max-w-md">
                        <x-panel::forms.tom-select
                            name="demo_small"
                            label="نوع"
                            :options="['a' => 'نوع A', 'b' => 'نوع B', 'c' => 'نوع C']"
                            size="sm"
                        />
                    </div>
                </div>

            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">فرم‌ها</h2>
            <x-panel::ui.card title="فرم نمونه">
                <form class="gap-4 flex-col flex">
                    <x-panel::forms.input
                        name="name"
                        label="نام"
                        placeholder="نام خود را وارد کنید"
                        required
                    />

                    <x-panel::forms.input
                        name="email"
                        type="email"
                        label="ایمیل"
                        placeholder="example@domain.com"
                        required
                    />

                    <x-panel::forms.date
                        name="birth_date"
                        label="تاریخ تولد"
                    />

                    <x-panel::forms.select
                        name="department"
                        label="دپارتمان"
                        :options="['1' => 'فناوری اطلاعات', '2' => 'منابع انسانی', '3' => 'مالی']"
                        required
                    />

                    <x-panel::forms.radio
                        name="is_active"
                        label="وضعیت کاربر"
                        :options="['1' => 'فعال', '0' => 'غیرفعال']"
                        value="1"
                    />

                    <x-panel::forms.tom-select
                        name="priority"
                        label="اولویت"
                        :options="['low' => 'کم', 'medium' => 'متوسط', 'high' => 'زیاد', 'urgent' => 'فوری']"
                        value="medium"
                    />

                    <x-panel::forms.textarea
                        name="description"
                        label="توضیحات"
                        placeholder="توضیحات خود را وارد کنید"
                        rows="4"
                    />

                    <div class="flex gap-4">
                        <x-panel::ui.button type="submit" variant="primary" icon="fa-save">
                            ذخیره
                        </x-panel::ui.button>
                        <x-panel::ui.button type="button" variant="secondary" icon="fa-times">
                            انصراف
                        </x-panel::ui.button>
                    </div>
                </form>
            </x-panel::ui.card>

            <div class="mt-6">
                <x-panel::ui.profile-image-upload name="profile_image" label="تصویر پروفایل" :standalone="true"/>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">نمایش اطلاعات (کامپوننت عمومی)</h2>
            <x-panel::ui.info-section
                :items="[
                    ['label' => 'کد پرسنلی', 'value' => 'EMP001'],
                    ['label' => 'موقعیت شغلی', 'value' => 'مدیر فروش'],
                    ['label' => 'دپارتمان', 'value' => 'فروش'],
                    ['label' => 'مدیر مستقیم', 'value' => 'محمد رضایی'],
                    ['label' => 'تاریخ استخدام', 'value' => '۱۳۹۹/۱۲/۱۰', 'colspan' => 2],
                ]"
                accentClass="border-green-500/20"
                :columns="2"
            />
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">اطلاعات سیستمی (کامپوننت)</h2>
            <div class="max-w-lg">
                <x-panel::ui.system-info
                    createdAt="۱۴۰۲/۰۸/۱۲"
                    lastLoginAt="۲ ساعت پیش"
                    :mobileVerified="true"
                    :emailVerified="true"
                />
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">عملیات سریع (کامپوننت)</h2>
            <div class="max-w-lg">
                <x-panel::ui.quick-actions :actions="[
                    ['label' => 'بازنشانی رمز عبور', 'icon' => 'fa-solid fa-key'],
                    ['label' => 'ارسال ایمیل', 'icon' => 'fa-solid fa-envelope'],
                    ['label' => 'مسدود کردن کاربر', 'icon' => 'fa-solid fa-ban'],
                    ['label' => 'حذف کاربر', 'icon' => 'fa-solid fa-trash', 'variant' => 'danger'],
                ]"/>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">نمودار (کامپوننت عمومی)</h2>

            <div class="bg-bg-primary border border-border-light rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-text-primary leading-snug mb-6">نمودار ساختار سازمانی</h2>

                <div class="flex justify-center items-start gap-8 overflow-x-auto pb-4">
                    <div class="flex flex-col items-center min-w-[200px]">
                        <x-panel::ui.diagram-node
                            title="مدیریت"
                            manager="محمد رضایی"
                            count="۸"
                            icon="fa-solid fa-building"
                            cardClass="bg-primary text-white"
                        />
                        <div class="w-0.5 h-8 bg-border-medium"></div>
                        <div class="flex gap-4">
                            <x-panel::ui.diagram-subnode
                                label="مدیریت اجرایی"
                                count="۵"
                                boxClass="bg-blue-50 border-2 border-blue-200"
                                labelClass="text-blue-800"
                                countClass="text-blue-600"
                            />
                        </div>
                    </div>

                    <div class="flex flex-col items-center min-w-[200px]">
                        <x-panel::ui.diagram-node
                            title="فروش"
                            manager="فاطمه محمدی"
                            count="۴۲"
                            icon="fa-solid fa-chart-line"
                            cardClass="bg-green-500 text-white"
                        />
                        <div class="w-0.5 h-8 bg-border-medium"></div>
                        <div class="flex gap-4">
                            <x-panel::ui.diagram-subnode
                                label="فروش تهران"
                                count="۲۵"
                                boxClass="bg-green-50 border-2 border-green-200"
                                labelClass="text-green-800"
                                countClass="text-green-600"
                            />
                            <x-panel::ui.diagram-subnode
                                label="فروش شهرستان"
                                count="۱۷"
                                boxClass="bg-green-50 border-2 border-green-200"
                                labelClass="text-green-800"
                                countClass="text-green-600"
                            />
                        </div>
                    </div>

                    <div class="flex flex-col items-center min-w-[200px]">
                        <x-panel::ui.diagram-node
                            title="فنی"
                            manager="رضا موسوی"
                            count="۳۵"
                            icon="fa-solid fa-code"
                            cardClass="bg-purple-500 text-white"
                        />
                        <div class="w-0.5 h-8 bg-border-medium"></div>
                        <div class="flex gap-4">
                            <x-panel::ui.diagram-subnode
                                label="توسعه نرم‌افزار"
                                count="۱۸"
                                boxClass="bg-purple-50 border-2 border-purple-200"
                                labelClass="text-purple-800"
                                countClass="text-purple-600"
                            />
                            <x-panel::ui.diagram-subnode
                                label="پشتیبانی"
                                count="۱۲"
                                boxClass="bg-purple-50 border-2 border-purple-200"
                                labelClass="text-purple-800"
                                countClass="text-purple-600"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-4">دسترسی‌های نقش (Demo)</h2>

            <x-panel::permissions.role-header roleName="مدیر سیستم" usersCount="2" />

            @php
            $standardPermissions = [
                'list'   => ['id' => 'list', 'name' => 'مشاهده لیست', 'icon' => 'fa-solid fa-list'],
                'show'   => ['id' => 'show', 'name' => 'مشاهده جزئیات', 'icon' => 'fa-solid fa-eye'],
                'store'  => ['id' => 'store', 'name' => 'ایجاد', 'icon' => 'fa-solid fa-plus'],
                'update' => ['id' => 'update', 'name' => 'ویرایش', 'icon' => 'fa-solid fa-pen'],
                'delete' => ['id' => 'delete', 'name' => 'حذف', 'icon' => 'fa-solid fa-trash'],
                'import' => ['id' => 'import', 'name' => 'ایمپورت داده', 'icon' => 'fa-solid fa-file-import'],
                'export' => ['id' => 'export', 'name' => 'خروجی گرفتن', 'icon' => 'fa-solid fa-file-export'],
            ];

            $modules = [
                [
                    'id' => 'org',
                    'name' => 'ساختار سازمانی',
                    'icon' => 'fa-solid fa-sitemap',
                    'color' => 'blue',
                    'entities' => [
                        ['id' => 'users', 'name' => 'کاربران', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => true]],
                        ['id' => 'departments', 'name' => 'دپارتمان‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
                        ['id' => 'roles', 'name' => 'نقش‌ها', 'permissions' => ['list' => true, 'show' => false, 'store' => false, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
                    ],
                ],
                [
                    'id' => 'documents',
                    'name' => 'مدیریت اسناد و فایل‌ها',
                    'icon' => 'fa-solid fa-folder-open',
                    'color' => 'amber',
                    'entities' => [
                        ['id' => 'files', 'name' => 'فایل‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => false, 'delete' => false, 'import' => false, 'export' => true]],
                        ['id' => 'folders', 'name' => 'پوشه‌ها', 'permissions' => ['list' => true, 'show' => true, 'store' => true, 'update' => false, 'delete' => false, 'import' => false, 'export' => false]],
                    ],
                ],
            ];
            @endphp

            <div class="space-y-6">
                @foreach($modules as $module)
                    <x-panel::permissions.module-card :module="$module" :standard-permissions="$standardPermissions" />
                @endforeach
            </div>
        </section>

        <!-- Tooltip Testing Section -->
        <section>
            <h2 class="text-xl font-bold mb-4">تست Tooltip</h2>
            <div class="bg-white border border-border-light rounded-2xl p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Tooltip با title attribute</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn btn-primary" title="این یک tooltip با title است">
                                <i class="fa-solid fa-info-circle ml-2"></i>
                                نمایش Tooltip
                            </button>
                            <button class="btn btn-secondary" title="این دکمه عملیات ذخیره را انجام می‌دهد">
                                <i class="fa-solid fa-save ml-2"></i>
                                ذخیره
                            </button>
                            <a href="#" class="btn btn-success" title="برای دانلود فایل کلیک کنید">
                                <i class="fa-solid fa-download ml-2"></i>
                                دانلود
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Tooltip با data-tooltip attribute</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn btn-warning" data-tooltip="این tooltip با data-tooltip است">
                                <i class="fa-solid fa-exclamation-triangle ml-2"></i>
                                هشدار
                            </button>
                            <button class="btn btn-danger" data-tooltip="حذف دائمی - این عمل قابل بازگشت نیست!">
                                <i class="fa-solid fa-trash ml-2"></i>
                                حذف
                            </button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Tooltip با aria-label</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn btn-outline" aria-label="ویرایش اطلاعات کاربر">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn btn-outline" aria-label="مشاهده جزئیات">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-outline" aria-label="اشتراک‌گذاری با دیگران">
                                <i class="fa-solid fa-share"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">آیکون‌ها با Tooltip</h3>
                        <div class="flex flex-wrap gap-4">
                            <i class="fa-solid fa-home text-2xl text-blue-500 cursor-pointer" title="صفحه اصلی"></i>
                            <i class="fa-solid fa-user text-2xl text-green-500 cursor-pointer" title="پروفایل کاربر"></i>
                            <i class="fa-solid fa-cog text-2xl text-gray-500 cursor-pointer" title="تنظیمات"></i>
                            <i class="fa-solid fa-bell text-2xl text-yellow-500 cursor-pointer" title="اعلان‌ها"></i>
                            <i class="fa-solid fa-envelope text-2xl text-red-500 cursor-pointer" title="پیام‌ها"></i>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Input fields با Tooltip</h3>
                        <div class="space-y-3">
                            <input type="text" class="form-input" placeholder="نام کاربری" title="نام کاربری باید حداقل 3 کاراکتر باشد">
                            <input type="email" class="form-input" placeholder="ایمیل" title="ایمیل معتبر وارد کنید">
                            <input type="password" class="form-input" placeholder="رمز عبور" data-tooltip="رمز عبور باید حداقل 8 کاراکتر و شامل حروف و اعداد باشد">
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">Links با Tooltip</h3>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="text-blue-600 hover:text-blue-800" title="مشاهده مستندات کامل">مستندات</a>
                            <a href="#" class="text-green-600 hover:text-green-800" title="تماس با پشتیبانی فنی">پشتیبانی</a>
                            <a href="#" class="text-purple-600 hover:text-purple-800" title="درباره شرکت و تیم ما">درباره ما</a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-3">متن بلند در Tooltip</h3>
                        <button class="btn btn-info" title="این یک tooltip با متن بلندتر است که برای نمایش اطلاعات بیشتر استفاده می‌شود و باید به درستی نمایش داده شود">
                            <i class="fa-solid fa-question-circle ml-2"></i>
                            راهنما
                        </button>
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-panel::layouts.dashboard>
