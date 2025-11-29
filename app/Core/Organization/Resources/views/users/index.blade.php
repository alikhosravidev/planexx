@php
    $title = $pageTitle ?? 'مدیریت کاربران';
    $currentPage = 'org-' . ($userType === 'employee' ? 'employees' : ($userType === 'customer' ? 'customers' : ($userType === 'user' ? 'regular-users' : 'users')));
    
    $createUrl = route('org.users.index') . ($userType ? '?type=' . $userType : '');
    $createLabel = isset($typeLabels[$userType]) ? 'افزودن ' . $typeLabels[$userType]['singular'] . ' جدید' : 'افزودن کاربر جدید';
    $actionButtons = [
        ['label' => $createLabel, 'url' => $createUrl, 'icon' => 'fa-solid fa-plus', 'type' => 'primary'],
    ];
@endphp

<x-layouts.app :title="$title">
    <div class="flex min-h-screen">
        <x-dashboard.sidebar
            menu-name="org.sidebar"
            :current-page="$currentPage"
            header-variant="back"
            module-title="ساختار سازمانی"
            module-icon="fa-solid fa-sitemap"
        />

        <main class="flex-1 flex flex-col">
            <x-dashboard.module-header 
                :page-title="$title" 
                :breadcrumbs="$breadcrumbs" 
                :action-buttons="$actionButtons"
            />

            <div class="flex-1 p-6 lg:p-8">
                
                <!-- فیلترها و جستجو -->
                <div class="bg-bg-primary border border-border-light rounded-2xl p-6 mb-6">
                    <form method="GET" action="{{ route('org.users.index') }}" class="flex flex-wrap items-stretch gap-4">
                        @if($userType)
                            <input type="hidden" name="type" value="{{ $userType }}">
                        @endif
                        
                        <!-- جستجو -->
                        <div class="flex-1 min-w-[250px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                            <div class="flex items-stretch">
                                <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                                    جستجو
                                </label>
                                <input type="text" 
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal"
                                       placeholder="نام، موبایل یا ایمیل">
                            </div>
                        </div>
                        
                        @if($userType === 'employee' && !empty($departments))
                        <!-- دپارتمان (فقط برای کارکنان) -->
                        <div class="flex-1 min-w-[200px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                            <div class="flex items-stretch">
                                <label class="bg-bg-label border-l border-border-light min-w-[100px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                                    دپارتمان
                                </label>
                                <select name="department_id" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                                    <option value="">همه دپارتمان‌ها</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept['id'] }}" {{ request('department_id') == $dept['id'] ? 'selected' : '' }}>
                                            {{ $dept['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        
                        <!-- وضعیت -->
                        <div class="flex-1 min-w-[180px] border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
                            <div class="flex items-stretch">
                                <label class="bg-bg-label border-l border-border-light min-w-[80px] px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal">
                                    وضعیت
                                </label>
                                <select name="status" class="flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent cursor-pointer leading-normal">
                                    <option value="">همه</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>فعال</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- دکمه‌های عملیاتی -->
                        <div class="flex items-center gap-2">
                            <button type="submit" class="bg-primary text-white px-xl py-3.5 rounded-lg font-medium hover:bg-blue-700 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                                <i class="fa-solid fa-search ml-2"></i>
                                <span>اعمال فیلتر</span>
                            </button>
                            <a href="{{ route('org.users.index') }}{{ $userType ? '?type=' . $userType : '' }}" class="bg-bg-secondary text-text-secondary border border-border-medium px-xl py-3.5 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200 text-base leading-normal whitespace-nowrap">
                                <i class="fa-solid fa-rotate-right ml-2"></i>
                                <span>پاک کردن</span>
                            </a>
                        </div>
                        
                    </form>
                </div>
                
                <!-- جدول کاربران -->
                <div class="bg-white border border-border-light rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-bg-secondary border-b border-border-light">
                                <tr>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">
                                        {{ $userType === 'employee' ? 'کارمند' : ($userType === 'customer' ? 'مشتری' : 'کاربر') }}
                                    </th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">شماره موبایل</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">ایمیل</th>
                                    @if(!$userType)
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">نوع کاربر</th>
                                    @endif
                                    @if($userType === 'employee' || !$userType)
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">موقعیت شغلی</th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">دپارتمان</th>
                                    @endif
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-text-primary leading-normal">وضعیت</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-text-primary leading-normal">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b border-border-light last:border-0 hover:bg-bg-secondary transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                                    @if(!empty($user['image_url']))
                                                        <img src="{{ $user['image_url'] }}" alt="{{ $user['full_name'] }}" class="w-full h-full rounded-full object-cover">
                                                    @else
                                                        <span class="text-primary font-semibold text-base">{{ mb_substr($user['full_name'] ?? 'U', 0, 1) }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-base text-text-primary font-medium leading-normal">{{ $user['full_name'] ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-base text-text-secondary leading-normal" dir="ltr">{{ $user['mobile'] ?? '-' }}</td>
                                        <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $user['email'] ?? '-' }}</td>
                                        @if(!$userType)
                                        <td class="px-6 py-4">
                                            @php
                                                $typeColors = [
                                                    'employee' => 'bg-green-50 text-green-700',
                                                    'customer' => 'bg-blue-50 text-blue-700',
                                                    'user' => 'bg-gray-100 text-gray-700',
                                                ];
                                                $userTypeKey = $user['user_type'] ?? 'user';
                                                $typeColor = $typeColors[$userTypeKey] ?? 'bg-gray-100 text-gray-700';
                                                $typeLabel = $typeLabels[$userTypeKey]['singular'] ?? 'کاربر';
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 {{ $typeColor }} px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                                                {{ $typeLabel }}
                                            </span>
                                        </td>
                                        @endif
                                        @if($userType === 'employee' || !$userType)
                                        <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $user['job_position']['name'] ?? '-' }}</td>
                                        <td class="px-6 py-4 text-base text-text-secondary leading-normal">{{ $user['department']['name'] ?? '-' }}</td>
                                        @endif
                                        <td class="px-6 py-4">
                                            @if($user['is_active'] ?? false)
                                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                                                    <i class="fa-solid fa-circle text-[6px]"></i>
                                                    فعال
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium leading-normal">
                                                    <i class="fa-solid fa-circle text-[6px]"></i>
                                                    غیرفعال
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                @if(($user['user_type'] ?? '') === 'employee')
                                                <button 
                                                    onclick="openAccessModal({{ $user['id'] }}, '{{ addslashes($user['full_name'] ?? '') }}')" 
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-amber-600 hover:bg-amber-50 rounded transition-all duration-200" 
                                                    title="تنظیم سطح دسترسی">
                                                    <i class="fa-solid fa-user-shield"></i>
                                                </button>
                                                @endif
                                                <a href="#" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="مشاهده جزئیات">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="#" class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-primary hover:bg-bg-secondary rounded transition-all duration-200" title="ویرایش">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button 
                                                    onclick="deleteUser({{ $user['id'] }})" 
                                                    class="w-8 h-8 flex items-center justify-center text-text-muted hover:text-red-600 hover:bg-red-50 rounded transition-all duration-200" 
                                                    title="حذف">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $userType === 'employee' || !$userType ? 8 : 6 }}" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-text-muted">
                                                <i class="fa-solid fa-users text-4xl mb-3 opacity-30"></i>
                                                <p class="text-base">کاربری یافت نشد</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(!empty($users) && !empty($pagination))
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-border-light flex items-center justify-between">
                        <div class="text-sm text-text-secondary leading-normal">
                            @php
                                $countLabel = $userType === 'employee' ? 'کارمند' : ($userType === 'customer' ? 'مشتری' : ($userType === 'user' ? 'کاربر' : 'کاربر'));
                                $from = $pagination['from'] ?? 1;
                                $to = $pagination['to'] ?? count($users);
                                $total = $pagination['total'] ?? count($users);
                            @endphp
                            نمایش <span class="font-semibold text-text-primary">{{ $from }}</span> تا <span class="font-semibold text-text-primary">{{ $to }}</span> از <span class="font-semibold text-text-primary">{{ $total }}</span> {{ $countLabel }}
                        </div>
                        <div class="flex items-center gap-2">
                            @if(($pagination['current_page'] ?? 1) > 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => ($pagination['current_page'] ?? 1) - 1]) }}" class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            @else
                                <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary opacity-50 cursor-not-allowed" disabled>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            @endif
                            
                            <button class="px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium">{{ $pagination['current_page'] ?? 1 }}</button>
                            
                            @if(($pagination['current_page'] ?? 1) < ($pagination['last_page'] ?? 1))
                                <a href="{{ request()->fullUrlWithQuery(['page' => ($pagination['current_page'] ?? 1) + 1]) }}" class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary hover:bg-bg-secondary transition-all duration-200">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </a>
                            @else
                                <button class="px-3 py-2 border border-border-medium rounded-lg text-sm text-text-secondary opacity-50 cursor-not-allowed" disabled>
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
            </div>
            
        </main>
        
    </div>
    
    <!-- مودال تنظیم سطح دسترسی -->
    <x-org.access-modal />
    
    @push('scripts')
    <script>
        let currentUserId = null;
        
        function openAccessModal(userId, userName) {
            currentUserId = userId;
            document.getElementById('accessModalUserName').textContent = userName;
            document.getElementById('accessModal').classList.remove('hidden');
            document.getElementById('accessModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeAccessModal() {
            document.getElementById('accessModal').classList.add('hidden');
            document.getElementById('accessModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
            currentUserId = null;
            
            document.getElementById('primaryRole').value = '';
            document.querySelectorAll('#accessModal input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
        
        function saveAccessSettings() {
            const primaryRole = document.getElementById('primaryRole').value;
            const secondaryRoles = [];
            document.querySelectorAll('#accessModal input[type="checkbox"]:checked').forEach(cb => {
                secondaryRoles.push(cb.value);
            });
            
            console.log('User ID:', currentUserId);
            console.log('Primary Role:', primaryRole);
            console.log('Secondary Roles:', secondaryRoles);
            
            alert('تغییرات با موفقیت ذخیره شد');
            closeAccessModal();
        }
        
        function deleteUser(userId) {
            if (!confirm('آیا از حذف این کاربر اطمینان دارید؟')) {
                return;
            }
            
            console.log('Delete user:', userId);
            alert('کاربر با موفقیت حذف شد');
        }
        
        document.getElementById('accessModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAccessModal();
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('accessModal')?.classList.contains('hidden')) {
                closeAccessModal();
            }
        });
    </script>
    @endpush
</x-layouts.app>
