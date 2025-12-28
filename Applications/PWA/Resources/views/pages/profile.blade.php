@php
    $currentTab = 'profile';

    $userName = $user['full_name'] ?? 'کاربر';
    $avatar = $user['avatar']['url'] ?? null;
    $department = $user['departments'][0]['name'] ?? '';
    $position = $user['job_position']['name'] ?? ($user['primary_roles'][0]['title'] ?? '');

    $userStats = [
        'daysWithCompany' => $user['collaboration_days'] ?? '—',
        'tasksCompleted' => $user['completed_tasks'] ?? '—',
        'score' => $user['score'] ?? '—',
        'level' => $user['level'] ?? 'عادی',
    ];

    $infoGroups = [
        [
            'title' => 'اطلاعات شخصی',
            'icon' => 'fa-user',
            'items' => [
                ['label' => 'نام و نام خانوادگی', 'value' => $user['full_name'] ?? '—', 'icon' => 'fa-id-card'],
                ['label' => 'کد ملی', 'value' => $user['national_code'] ?? '—', 'icon' => 'fa-fingerprint'],
                ['label' => 'تاریخ تولد', 'value' => $user['birth_date']['human']['date'] ?? $user['birth_date'] ?? '—', 'icon' => 'fa-cake-candles'],
            ],
        ],
        [
            'title' => 'اطلاعات تماس',
            'icon' => 'fa-address-book',
            'items' => [
                ['label' => 'ایمیل', 'value' => $user['email'] ?? '—', 'icon' => 'fa-envelope'],
                ['label' => 'موبایل', 'value' => $user['mobile'] ?? '—', 'icon' => 'fa-phone'],
            ],
        ],
        [
            'title' => 'اطلاعات سازمانی',
            'icon' => 'fa-building',
            'items' => [
                ['label' => 'کد پرسنلی', 'value' => $user['employee_code'] ?? '—', 'icon' => 'fa-hashtag'],
                ['label' => 'واحد سازمانی', 'value' => $department, 'icon' => 'fa-sitemap'],
                ['label' => 'سمت', 'value' => $position, 'icon' => 'fa-briefcase'],
                ['label' => 'مدیر مستقیم', 'value' => $user['direct_manager']['full_name'] ?? '—', 'icon' => 'fa-user-tie'],
                ['label' => 'نوع قرارداد', 'value' => $user['contract_type'] ?? '—', 'icon' => 'fa-file-contract'],
                ['label' => 'تاریخ استخدام', 'value' => $user['employment_date']['human']['date'] ?? $user['employment_date'] ?? '—', 'icon' => 'fa-calendar-check'],
                ['label' => 'محل کار', 'value' => $user['work_location'] ?? '—', 'icon' => 'fa-location-dot'],
            ],
        ],
    ];

    $appSettings = [
        [
            'title' => 'اعلان‌ها',
            'description' => 'دریافت اعلان‌های اپلیکیشن',
            'icon' => 'fa-bell',
            'enabled' => true,
        ],
        [
            'title' => 'حالت تاریک',
            'description' => 'فعال‌سازی تم تاریک',
            'icon' => 'fa-moon',
            'enabled' => false,
        ],
    ];

    $helpLinks = [
        ['title' => 'راهنمای استفاده', 'icon' => 'fa-circle-question', 'url' => '#'],
        ['title' => 'تماس با پشتیبانی', 'icon' => 'fa-headset', 'url' => '#'],
        ['title' => 'درباره اپلیکیشن', 'icon' => 'fa-circle-info', 'url' => '#'],
    ];
@endphp

<x-pwa::layouts.app title="پروفایل" :current-tab="$currentTab" :show-header="false">
    <x-slot:customHeader>
        <!-- Header with Cover -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-700 h-36 rounded-b-[32px] relative">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
        </div>
    </x-slot:customHeader>

    <!-- Profile Card (Overlapping) -->
    <div class="px-5 -mt-20">
        <div class="bg-white rounded-3xl shadow-lg p-5">
            <!-- Avatar and Basic Info -->
            <div class="flex flex-col items-center mb-5">
                <div class="relative mb-3">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $userName }}" class="w-20 h-20 rounded-2xl shadow-lg object-cover border-4 border-white">
                    @else
                        <div class="w-20 h-20 rounded-2xl shadow-lg bg-slate-200 flex items-center justify-center border-4 border-white">
                            <i class="fa-solid fa-user text-slate-400 text-3xl"></i>
                        </div>
                    @endif
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fa-solid fa-trophy text-white text-sm"></i>
                    </div>
                </div>
                <h1 class="text-slate-900 text-lg font-bold mb-0.5">{{ $userName }}</h1>
                <p class="text-slate-600 text-sm">{{ $position }}</p>
                <p class="text-slate-500 text-xs">{{ $department }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-4 gap-2 mb-3">
                <div class="text-center p-2 bg-gray-50 rounded-xl">
                    <p class="text-slate-900 text-sm font-bold">{{ $userStats['daysWithCompany'] }}</p>
                    <p class="text-slate-500 text-[10px]">روز همکاری</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded-xl">
                    <p class="text-slate-900 text-sm font-bold">{{ $userStats['tasksCompleted'] }}</p>
                    <p class="text-slate-500 text-[10px]">وظیفه انجام</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded-xl">
                    <p class="text-slate-900 text-sm font-bold">{{ $userStats['score'] }}</p>
                    <p class="text-slate-500 text-[10px]">امتیاز</p>
                </div>
                <div class="text-center p-2 bg-amber-50 rounded-xl">
                    <p class="text-amber-700 text-sm font-bold">{{ $userStats['level'] }}</p>
                    <p class="text-amber-600 text-[10px]">سطح</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-5 py-6 space-y-6">

        <!-- Info Groups -->
        @foreach($infoGroups as $group)
            <div>
                <div class="flex items-center gap-2 mb-3 mt-3">
                    <div class="w-7 h-7 bg-slate-900 rounded-lg flex items-center justify-center">
                        <i class="fa-solid {{ $group['icon'] }} text-white text-xs"></i>
                    </div>
                    <h2 class="text-slate-900 text-base font-bold">{{ $group['title'] }}</h2>
                </div>
                <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100">
                    @foreach($group['items'] as $item)
                        <div class="p-4 flex items-center gap-3">
                            <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $item['icon'] }} text-slate-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-slate-500 text-xs mb-0.5">{{ $item['label'] }}</p>
                                <p class="text-slate-900 text-sm font-medium truncate">{{ $item['value'] }}</p>
                            </div>
                            <i class="fa-solid fa-lock text-gray-300 text-xs" title="غیرقابل ویرایش"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- App Settings -->
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 bg-slate-900 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-gear text-white text-xs"></i>
                </div>
                <h2 class="text-slate-900 text-base font-bold">تنظیمات</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100">
                @foreach($appSettings as $setting)
                    <div class="p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $setting['icon'] }} text-slate-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-slate-900 text-sm font-medium">{{ $setting['title'] }}</p>
                                <p class="text-slate-500 text-xs">{{ $setting['description'] }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0" onclick="toggleSwitch(this)">
                            <input type="checkbox" class="sr-only" {{ $setting['enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 rounded-full transition-colors {{ $setting['enabled'] ? 'bg-slate-900' : 'bg-gray-200' }}">
                                <span class="block w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200 mt-0.5 {{ $setting['enabled'] ? 'mr-0.5' : 'mr-[22px]' }}"></span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Help Links -->
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 bg-slate-900 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-circle-question text-white text-xs"></i>
                </div>
                <h2 class="text-slate-900 text-base font-bold">راهنما و پشتیبانی</h2>
            </div>
            <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-100">
                @foreach($helpLinks as $link)
                    <a href="{{ $link['url'] }}" class="p-4 flex items-center gap-3 hover:bg-gray-50 transition-all">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $link['icon'] }} text-slate-500 text-sm"></i>
                        </div>
                        <span class="flex-1 text-slate-900 text-sm font-medium">{{ $link['title'] }}</span>
                        <i class="fa-solid fa-chevron-left text-slate-400 text-xs"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Version Info -->
        <div class="text-center py-4">
            <p class="text-slate-400 text-xs">نسخه ۱.۰.۰</p>
        </div>

        <!-- Logout Button -->
        <button type="button" onclick="confirmLogout()" class="w-full bg-red-600 text-white py-4 rounded-2xl font-bold text-base hover:bg-red-700 active:bg-red-800 transition-all flex items-center justify-center gap-2 shadow-lg">
            <i class="fa-solid fa-right-from-bracket"></i>
            خروج از حساب کاربری
        </button>

    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[100]" onclick="closeLogoutModal()">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-right-from-bracket text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-slate-900 text-lg font-bold text-center mb-2">خروج از حساب؟</h3>
            <p class="text-slate-600 text-sm text-center mb-6 leading-relaxed">
                آیا مطمئن هستید که می‌خواهید از حساب کاربری خود خارج شوید؟
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutModal()" class="flex-1 bg-gray-100 text-slate-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                    انصراف
                </button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition-all">
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            function toggleSwitch(label) {
                const input = label.querySelector('input');
                const track = label.querySelector('div');
                const knob = label.querySelector('span');

                input.checked = !input.checked;

                if (input.checked) {
                    track.classList.remove('bg-gray-200');
                    track.classList.add('bg-slate-900');
                    knob.classList.remove('mr-[22px]');
                    knob.classList.add('mr-0.5');
                } else {
                    track.classList.remove('bg-slate-900');
                    track.classList.add('bg-gray-200');
                    knob.classList.remove('mr-0.5');
                    knob.classList.add('mr-[22px]');
                }
            }

            function confirmLogout() {
                document.getElementById('logoutModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLogoutModal() {
                document.getElementById('logoutModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const logoutModal = document.getElementById('logoutModal');
                    if (!logoutModal.classList.contains('hidden')) {
                        closeLogoutModal();
                    }
                }
            });
        </script>
    </x-slot:scripts>
</x-pwa::layouts.app>
