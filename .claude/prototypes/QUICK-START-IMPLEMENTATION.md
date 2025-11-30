# Quick Start Implementation Guide

**Target Audience**: Developers starting prototype implementation  
**Estimated Time**: 2-3 days for Phase 1  
**Prerequisites**: Docker environment running

---

## 🎯 Phase 1: Foundation Setup (Days 1-3)

This guide walks you through setting up the foundation for implementing prototypes.

---

## Day 1: Design System & Assets

### Step 1: Extract Design Tokens from Prototypes

**Goal**: Configure Tailwind with prototype design system

#### 1.1 Update Tailwind Config

Read the prototype CSS variables:

```bash
cat docs/prototypes/assets/css/variables.css
```

Update `tailwind.config.js`:

```javascript
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0f172a',      // slate-900
        'bg-primary': '#ffffff',
        'bg-secondary': '#f8fafc',
        'text-primary': '#0f172a',
        'text-secondary': '#64748b',
        'text-muted': '#94a3b8',
        'border-light': '#e2e8f0',
        'border-medium': '#cbd5e1',
      },
      spacing: {
        'xs': '0.25rem',    // 4px
        'sm': '0.5rem',     // 8px
        'md': '0.75rem',    // 12px
        'lg': '1rem',       // 16px
        'xl': '1.5rem',     // 24px
        '2xl': '2rem',      // 32px
        '3xl': '3rem',      // 48px
        '4xl': '4rem',      // 64px
        '5xl': '6rem',      // 96px
      },
      borderRadius: {
        'xl': '0.75rem',    // 12px
        '2xl': '1rem',      // 16px
        '3xl': '1.5rem',    // 24px
      },
      fontFamily: {
        'sahel': ['Sahel', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
```

#### 1.2 Set Up CSS Variables

Create `resources/css/variables.css`:

```css
@layer base {
  :root {
    /* Colors */
    --color-primary: #0f172a;
    --color-bg-primary: #ffffff;
    --color-bg-secondary: #f8fafc;
    --color-text-primary: #0f172a;
    --color-text-secondary: #64748b;
    --color-text-muted: #94a3b8;
    --color-border-light: #e2e8f0;
    --color-border-medium: #cbd5e1;
    
    /* Spacing */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 0.75rem;
    --spacing-lg: 1rem;
    --spacing-xl: 1.5rem;
    --spacing-2xl: 2rem;
    --spacing-3xl: 3rem;
    --spacing-4xl: 4rem;
    --spacing-5xl: 6rem;
    
    /* Border Radius */
    --radius-lg: 0.5rem;
    --radius-xl: 0.75rem;
    --radius-2xl: 1rem;
    --radius-3xl: 1.5rem;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
  }
}
```

#### 1.3 Update Main CSS

Update `resources/css/app.css`:

```css
@import 'variables.css';

@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  body {
    @apply font-sahel text-text-primary bg-bg-secondary;
    direction: rtl;
  }
  
  * {
    @apply leading-normal;
  }
}

@layer components {
  /* Add custom component styles here */
}
```

#### 1.4 Install Sahel Font

Download from prototype or CDN, add to `resources/css/app.css`:

```css
@font-face {
  font-family: 'Sahel';
  src: url('/fonts/Sahel.woff2') format('woff2'),
       url('/fonts/Sahel.woff') format('woff');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Sahel';
  src: url('/fonts/Sahel-Bold.woff2') format('woff2'),
       url('/fonts/Sahel-Bold.woff') format('woff');
  font-weight: bold;
  font-style: normal;
  font-display: swap;
}
```

Copy font files:

```bash
mkdir -p public/fonts
cp docs/prototypes/assets/fonts/* public/fonts/
```

#### 1.5 Build Assets

```bash
docker exec planexx_app npm run build
```

---

### Step 2: Create Base Layout Components

#### 2.1 Main App Layout

Create `resources/views/components/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{ $head ?? '' }}
</head>
<body class="bg-bg-secondary">
    {{ $slot }}
    
    {{ $scripts ?? '' }}
</body>
</html>
```

#### 2.2 Dashboard Layout

Create `resources/views/components/layouts/dashboard.blade.php`:

```blade
<x-layouts.app :title="$title ?? 'داشبورد'">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-dashboard.sidebar :current-page="$currentPage ?? 'dashboard'" />
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <x-dashboard.header :title="$title ?? 'داشبورد'" />
            
            <!-- Content -->
            <div class="flex-1 p-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-layouts.app>
```

#### 2.3 Auth Layout

Create `resources/views/components/layouts/auth.blade.php`:

```blade
<x-layouts.app :title="$title ?? 'ورود'">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700 p-4">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
```

---

### Step 3: Create UI Components

#### 3.1 Button Component

Create `resources/views/components/ui/button.blade.php`:

```blade
@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'loading' => false,
])

@php
$variants = [
    'primary' => 'bg-slate-900 text-white hover:bg-slate-800',
    'secondary' => 'bg-white text-slate-900 border border-slate-300 hover:bg-slate-50',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    'outline' => 'bg-transparent border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];

$classes = $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed $classes"]) }}
    @if($loading) disabled @endif
>
    @if($loading)
        <i class="fa-solid fa-spinner fa-spin"></i>
    @elseif($icon)
        <i class="{{ $icon }}"></i>
    @endif
    
    {{ $slot }}
</button>
```

**Usage**:
```blade
<x-ui.button variant="primary" icon="fa-save">
    ذخیره
</x-ui.button>

<x-ui.button variant="secondary" :loading="true">
    در حال بارگذاری...
</x-ui.button>
```

#### 3.2 Card Component

Create `resources/views/components/ui/card.blade.php`:

```blade
@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-border-light rounded-2xl shadow-sm']) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-border-light">
            @if($title)
                <h3 class="text-lg font-bold text-text-primary">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-text-secondary mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
```

**Usage**:
```blade
<x-ui.card title="عنوان کارت" subtitle="توضیحات کارت">
    محتوای کارت
</x-ui.card>
```

#### 3.3 Badge Component

Create `resources/views/components/ui/badge.blade.php`:

```blade
@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$variants = [
    'default' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
    'lg' => 'px-3 py-1.5 text-base',
];

$classes = $variants[$variant] . ' ' . $sizes[$size];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-lg font-medium $classes"]) }}>
    {{ $slot }}
</span>
```

**Usage**:
```blade
<x-ui.badge variant="success">منتشر شده</x-ui.badge>
<x-ui.badge variant="warning">در انتظار</x-ui.badge>
```

#### 3.4 Stat Card Component

Create `resources/views/components/dashboard/stat-card.blade.php`:

```blade
@props([
    'title',
    'value',
    'change' => null,
    'changeType' => 'neutral',
    'icon' => 'fa-chart-line',
    'color' => 'blue',
])

@php
$colors = [
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'orange' => 'bg-orange-100 text-orange-600',
    'red' => 'bg-red-100 text-red-600',
];

$changeColors = [
    'increase' => 'text-green-600',
    'decrease' => 'text-red-600',
    'neutral' => 'text-gray-600',
];
@endphp

<div class="bg-white border border-border-light rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 {{ $colors[$color] }} rounded-xl flex items-center justify-center">
            <i class="{{ $icon }} text-xl"></i>
        </div>
        
        @if($change)
            <span class="text-sm font-medium {{ $changeColors[$changeType] }}">
                {{ $change }}
            </span>
        @endif
    </div>
    
    <h3 class="text-3xl font-bold text-text-primary mb-1">{{ $value }}</h3>
    <p class="text-sm text-text-secondary">{{ $title }}</p>
</div>
```

**Usage**:
```blade
<x-dashboard.stat-card 
    title="تعداد کاربران"
    value="247"
    change="+12%"
    changeType="increase"
    icon="fa-users"
    color="blue"
/>
```

---

## Day 2: Dashboard Components

### Step 1: Create Dashboard Header

Create `resources/views/components/dashboard/header.blade.php`:

```blade
@props(['title' => 'داشبورد'])

<header class="bg-white border-b border-border-light px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Title & Breadcrumb -->
        <div>
            <h1 class="text-2xl font-bold text-text-primary">{{ $title }}</h1>
            
            @if(isset($breadcrumbs))
                <nav class="flex items-center gap-2 text-sm mt-1">
                    @foreach($breadcrumbs as $index => $crumb)
                        @if($index > 0)
                            <i class="fa-solid fa-chevron-left text-gray-400 text-xs"></i>
                        @endif
                        
                        @if(isset($crumb['url']) && $index < count($breadcrumbs) - 1)
                            <a href="{{ $crumb['url'] }}" class="text-text-secondary hover:text-text-primary">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-text-primary font-medium">{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative hidden md:block">
                <input 
                    type="text" 
                    placeholder="جستجو..." 
                    class="w-64 px-4 py-2 pr-10 border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent"
                >
                <i class="fa-solid fa-search absolute right-3 top-1/2 -translate-y-1/2 text-text-muted"></i>
            </div>
            
            <!-- Notifications -->
            <button class="relative p-2 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-bell text-xl text-text-secondary"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            
            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg">
                    <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" 
                         alt="User" 
                         class="w-8 h-8 rounded-full">
                    <span class="hidden md:block text-sm font-medium">{{ auth()->user()->full_name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition
                     class="absolute left-0 mt-2 w-48 bg-white border border-border-light rounded-xl shadow-lg py-2 z-50">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">
                        <i class="fa-solid fa-user ml-2"></i>
                        پروفایل
                    </a>
                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">
                        <i class="fa-solid fa-cog ml-2"></i>
                        تنظیمات
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fa-solid fa-sign-out ml-2"></i>
                            خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
```

### Step 2: Create Dashboard Sidebar

Create `resources/views/components/dashboard/sidebar.blade.php`:

```blade
@props(['currentPage' => 'dashboard'])

<aside class="w-64 bg-white border-l border-border-light flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-border-light">
        <a href="{{ route('web.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-layer-group text-white text-xl"></i>
            </div>
            <span class="text-xl font-bold text-text-primary">{{ config('app.name') }}</span>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('web.dashboard') }}" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'dashboard',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'dashboard',
               ])>
                <i class="fa-solid fa-chart-line text-lg"></i>
                <span class="font-medium">داشبورد</span>
            </a>
            
            <!-- Organization -->
            <a href="{{ route('admin.organization.index') }}" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'organization',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'organization',
               ])>
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span class="font-medium">ساختار سازمانی</span>
            </a>
            
            <!-- Knowledge Base -->
            <a href="{{ route('admin.knowledge.index') }}" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'knowledge',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'knowledge',
               ])>
                <i class="fa-solid fa-book text-lg"></i>
                <span class="font-medium">پایگاه تجربه</span>
            </a>
            
            <!-- Documents -->
            <a href="{{ route('admin.documents.index') }}" 
               @class([
                   'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                   'bg-primary text-white' => $currentPage === 'documents',
                   'text-text-secondary hover:bg-gray-50' => $currentPage !== 'documents',
               ])>
                <i class="fa-solid fa-folder text-lg"></i>
                <span class="font-medium">مدیریت اسناد</span>
            </a>
        </div>
    </nav>
    
    <!-- User Info -->
    <div class="p-4 border-t border-border-light">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" 
                 alt="User" 
                 class="w-10 h-10 rounded-full">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-text-primary truncate">
                    {{ auth()->user()->full_name }}
                </p>
                <p class="text-xs text-text-secondary truncate">
                    {{ auth()->user()->job_position?->title }}
                </p>
            </div>
        </div>
    </div>
</aside>
```

---

## Day 3: Form Components & Testing

### Step 1: Create Form Components

#### 3.1 Input Component

Create `resources/views/components/forms/input.blade.php`:

```blade
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'error' => null,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-text-primary mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-colors']) }}
        value="{{ old($name) }}"
    >
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
```

#### 3.2 Select Component

Create `resources/views/components/forms/select.blade.php`:

```blade
@props([
    'name',
    'label' => null,
    'options' => [],
    'placeholder' => 'انتخاب کنید',
    'required' => false,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-text-primary mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-colors']) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

#### 3.3 Textarea Component

Create `resources/views/components/forms/textarea.blade.php`:

```blade
@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-text-primary mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border border-border-light rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-colors resize-none']) }}
    >{{ old($name) }}</textarea>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### Step 2: Create Test Page

Create a test route in `routes/web.php`:

```php
Route::get('/test-components', function () {
    return view('test-components');
})->name('test.components');
```

Create `resources/views/test-components.blade.php`:

```blade
<x-layouts.dashboard title="تست کامپوننت‌ها" current-page="dashboard">
    <div class="space-y-8">
        <!-- Stat Cards -->
        <section>
            <h2 class="text-xl font-bold mb-4">کارت‌های آماری</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-dashboard.stat-card 
                    title="تعداد کاربران"
                    value="247"
                    change="+12%"
                    changeType="increase"
                    icon="fa-users"
                    color="blue"
                />
                <x-dashboard.stat-card 
                    title="کارمندان فعال"
                    value="189"
                    change="+8%"
                    changeType="increase"
                    icon="fa-user-tie"
                    color="green"
                />
                <x-dashboard.stat-card 
                    title="دپارتمان‌ها"
                    value="24"
                    change="+2"
                    changeType="increase"
                    icon="fa-sitemap"
                    color="purple"
                />
                <x-dashboard.stat-card 
                    title="موقعیت‌های شغلی"
                    value="38"
                    change="+5"
                    changeType="increase"
                    icon="fa-briefcase"
                    color="orange"
                />
            </div>
        </section>
        
        <!-- Buttons -->
        <section>
            <h2 class="text-xl font-bold mb-4">دکمه‌ها</h2>
            <div class="flex flex-wrap gap-4">
                <x-ui.button variant="primary" icon="fa-save">
                    ذخیره
                </x-ui.button>
                <x-ui.button variant="secondary" icon="fa-times">
                    انصراف
                </x-ui.button>
                <x-ui.button variant="danger" icon="fa-trash">
                    حذف
                </x-ui.button>
                <x-ui.button variant="outline" icon="fa-download">
                    دانلود
                </x-ui.button>
                <x-ui.button variant="primary" :loading="true">
                    در حال بارگذاری...
                </x-ui.button>
            </div>
        </section>
        
        <!-- Badges -->
        <section>
            <h2 class="text-xl font-bold mb-4">بج‌ها</h2>
            <div class="flex flex-wrap gap-2">
                <x-ui.badge variant="default">پیش‌فرض</x-ui.badge>
                <x-ui.badge variant="success">موفق</x-ui.badge>
                <x-ui.badge variant="warning">هشدار</x-ui.badge>
                <x-ui.badge variant="danger">خطا</x-ui.badge>
                <x-ui.badge variant="info">اطلاعات</x-ui.badge>
            </div>
        </section>
        
        <!-- Cards -->
        <section>
            <h2 class="text-xl font-bold mb-4">کارت‌ها</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.card title="کارت با عنوان" subtitle="این یک کارت نمونه است">
                    محتوای کارت در اینجا قرار می‌گیرد.
                </x-ui.card>
                <x-ui.card>
                    کارت بدون عنوان
                </x-ui.card>
            </div>
        </section>
        
        <!-- Forms -->
        <section>
            <h2 class="text-xl font-bold mb-4">فرم‌ها</h2>
            <x-ui.card title="فرم نمونه">
                <form>
                    <x-forms.input 
                        name="name"
                        label="نام"
                        placeholder="نام خود را وارد کنید"
                        required
                    />
                    
                    <x-forms.input 
                        name="email"
                        type="email"
                        label="ایمیل"
                        placeholder="example@domain.com"
                        required
                    />
                    
                    <x-forms.select 
                        name="department"
                        label="دپارتمان"
                        :options="['1' => 'فناوری اطلاعات', '2' => 'منابع انسانی', '3' => 'مالی']"
                        required
                    />
                    
                    <x-forms.textarea 
                        name="description"
                        label="توضیحات"
                        placeholder="توضیحات خود را وارد کنید"
                        rows="4"
                    />
                    
                    <div class="flex gap-4">
                        <x-ui.button type="submit" variant="primary" icon="fa-save">
                            ذخیره
                        </x-ui.button>
                        <x-ui.button type="button" variant="secondary" icon="fa-times">
                            انصراف
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </section>
    </div>
</x-layouts.dashboard>
```

### Step 3: Test Components

```bash
# Start development server
docker exec planexx_app composer dev

# Visit in browser
http://localhost/test-components
```

**Checklist**:
- [ ] All stat cards display correctly
- [ ] Buttons have proper styling and hover effects
- [ ] Badges show correct colors
- [ ] Cards render with and without titles
- [ ] Form inputs are styled correctly
- [ ] Form validation works
- [ ] Responsive design works on mobile
- [ ] RTL direction is correct
- [ ] Fonts load properly

---

## ✅ Phase 1 Completion Checklist

- [ ] Tailwind configured with prototype design tokens
- [ ] CSS variables set up
- [ ] Sahel font installed and working
- [ ] Base layouts created (app, dashboard, auth)
- [ ] UI components created (button, card, badge)
- [ ] Dashboard components created (header, sidebar, stat-card)
- [ ] Form components created (input, select, textarea)
- [ ] Test page created and all components working
- [ ] Responsive design verified
- [ ] RTL support verified
- [ ] Assets compiled successfully

---

## 🚀 Next Steps

After completing Phase 1, proceed to:

1. **Phase 2**: Authentication UI implementation
2. **Phase 3**: Dashboard implementation
3. **Phase 4**: Organization module UI

Refer to `PROTOTYPE-IMPLEMENTATION-PLAN.md` for detailed phase instructions.

---

## 📚 Resources

- **Prototype Files**: `docs/prototypes/`
- **Full Implementation Plan**: `.claude/PROTOTYPE-IMPLEMENTATION-PLAN.md`
- **Prototype Analysis**: `.claude/PROTOTYPE-ANALYSIS.md`
- **Architecture Guide**: `.claude/architecture.md`
- **AJAX System**: `.claude/ajax-system-overview.md`

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-27  
**Estimated Completion**: 2-3 days
