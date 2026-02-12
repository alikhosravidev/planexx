<?php
// تنظیمات اولیه
define('PROJECT_ROOT', dirname(dirname(dirname(__DIR__))));
require_once PROJECT_ROOT . '/_components/config.php';

// تنظیمات صفحه
$pageTitle = 'چارت سازمانی - نسخه پیشرفته';
$currentPage = 'organizational-chart-v2';
?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?> | <?= $siteConfig['name'] ?></title>
  
  <link href="https://cdn.jsdelivr.net/npm/sahel-font@3.4.0/dist/font-face.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#0f172a',
            'text-primary': '#0f172a',
            'text-secondary': '#475569',
            'text-muted': '#64748b',
            'bg-primary': '#ffffff',
            'bg-secondary': '#f8fafc',
            'border-light': '#f1f5f9',
            'border-medium': '#e2e8f0',
          },
        }
      }
    }
  </script>
<style>
  /* فونت Sahel برای همه عناصر */
  body, button, input, textarea, select, p, div, span, h1, h2, h3, h4, h5, h6 {
    font-family: 'Sahel', sans-serif !important;
  }
  
  /* آیکون های Font Awesome نباید فونت Sahel داشته باشند */
  .fa, .fas, .far, .fal, .fab {
    font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Pro', 'Font Awesome 6 Brands' !important;
  }

  html, body {
    margin: 0px;
    padding: 0px;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  #tree {
    width: 100%;
    height: 100%;
  }

  /* خطوط اتصال پشت عکس‌ها قرار بگیرند */
  .boc-link {
    z-index: 0 !important;
  }
  
  .boc-node {
    z-index: 1 !important;
  }

  /* دکمه‌های expand/collapse - خاکستری و ظریف */
  .boc-expander-icon {
    background: #9ca3af !important;
    opacity: 0.6 !important;
  }
  
  .boc-expander-icon:hover {
    opacity: 0.9 !important;
  }
</style>
</head>
<body class="bg-bg-secondary">
  <!--HTML-->

<script src="https://balkan.app/js/OrgChart.js"></script>



<div id="tree"></div>

<!-- دکمه برگشت -->
<div class="fixed top-6 left-6 z-50">
  <button onclick="history.back()" class="px-4 h-12 bg-white text-primary rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group border border-border-light">
    <span class="text-sm font-medium">برگشت</span>
    <i class="fa-solid fa-arrow-left text-lg group-hover:-translate-x-0.5 transition-transform duration-200"></i>
  </button>
</div>

<!-- دکمه‌های کنترل زوم و دانلود -->
<div class="fixed bottom-6 left-6 flex flex-col gap-3 z-50">
  <!-- دکمه دانلود PDF -->
  <button onclick="chart.exportToPDF({filename: 'organizational-chart.pdf'})" class="w-12 h-12 bg-primary text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center group">
    <i class="fa-solid fa-file-pdf text-lg"></i>
  </button>
  
  <!-- دکمه دانلود SVG -->
  <button onclick="chart.exportToSVG({filename: 'organizational-chart.svg'})" class="w-12 h-12 bg-primary text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center group">
    <i class="fa-solid fa-file-image text-lg"></i>
  </button>
  
  <!-- دکمه زوم این -->
  <button onclick="chart.zoom(true, [0.5, 0.5], true)" class="w-12 h-12 bg-primary text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center group">
    <i class="fa-solid fa-magnifying-glass-plus text-lg"></i>
  </button>
  
  <!-- دکمه زوم اوت -->
  <button onclick="chart.zoom(false, [0.5, 0.5], true)" class="w-12 h-12 bg-primary text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center group">
    <i class="fa-solid fa-magnifying-glass-minus text-lg"></i>
  </button>
  
  <!-- دکمه Fit (تنظیم اندازه به صفحه) -->
  <button onclick="chart.fit()" class="w-12 h-12 bg-primary text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center group">
    <i class="fa-solid fa-compress text-lg"></i>
  </button>
</div>

  <script>
//JavaScript

// تعریف template های سفارشی با رنگ‌های مختلف و استایل بهینه
OrgChart.templates.holding = Object.assign({}, OrgChart.templates.isla);
OrgChart.templates.holding.size = [220, 120];
OrgChart.templates.holding.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#1e3a8a" stroke-width="1" stroke="#1e40af" rx="5" ry="5"></rect>';
OrgChart.templates.holding.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
OrgChart.templates.holding.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
OrgChart.templates.holding.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#1e3a8a" stroke-width="4" fill="#1e3a8a" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

OrgChart.templates.brand = Object.assign({}, OrgChart.templates.isla);
OrgChart.templates.brand.size = [220, 120];
OrgChart.templates.brand.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#0ea5e9" stroke-width="1" stroke="#0284c7" rx="5" ry="5"></rect>';
OrgChart.templates.brand.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
OrgChart.templates.brand.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
OrgChart.templates.brand.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#0ea5e9" stroke-width="4" fill="#0ea5e9" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

OrgChart.templates.department = Object.assign({}, OrgChart.templates.isla);
OrgChart.templates.department.size = [220, 120];
OrgChart.templates.department.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#059669" stroke-width="1" stroke="#047857" rx="5" ry="5"></rect>';
OrgChart.templates.department.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
OrgChart.templates.department.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
OrgChart.templates.department.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#059669" stroke-width="4" fill="#059669" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

OrgChart.templates.team = Object.assign({}, OrgChart.templates.isla);
OrgChart.templates.team.size = [220, 120];
OrgChart.templates.team.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#d97706" stroke-width="1" stroke="#b45309" rx="5" ry="5"></rect>';
OrgChart.templates.team.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
OrgChart.templates.team.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
OrgChart.templates.team.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#d97706" stroke-width="4" fill="#d97706" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

OrgChart.templates.employee = Object.assign({}, OrgChart.templates.isla);
OrgChart.templates.employee.size = [220, 120];
OrgChart.templates.employee.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#7c3aed" stroke-width="1" stroke="#6d28d9" rx="5" ry="5"></rect>';
OrgChart.templates.employee.field_0 = '<text style="font-size: 16px; font-weight: 300;" fill="#ffffff" x="110" y="65" text-anchor="middle">{val}</text>';
OrgChart.templates.employee.field_1 = '<text style="font-size: 13px; font-weight: 300;" fill="#cbd5e1" x="110" y="85" text-anchor="middle">{val}</text>';
OrgChart.templates.employee.img_0 = '<clipPath id="{randId}"><circle cx="110" cy="20" r="28"></circle></clipPath><circle stroke="#7c3aed" stroke-width="4" fill="#7c3aed" cx="110" cy="20" r="30"></circle><circle stroke="#ffffff" stroke-width="2" fill="none" cx="110" cy="20" r="28"></circle><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="82" y="-8" width="56" height="56"></image>';

let chart = new OrgChart(document.getElementById("tree"), {
    mouseScrool: OrgChart.none,
    layout: OrgChart.mixed,
    enableSearch: false,
    linkBinding: {
        link_field_0: "linkStyle"
    },
    orderBy: "order",
    collapse: {
        level: 2  // برندها و زیرمجموعه‌هایشان collapse می‌شوند
    },
    nodeBinding: {
        img_0: "img",
        field_0: "name",
        field_1: "title"
    },
    tags: {
        "holding": {
            template: "holding"
        },
        "brand": {
            template: "brand"
        },
        "department": {
            template: "department"
        },
        "team": {
            template: "team"
        },
        "employee": {
            template: "employee"
        }
    }
});

chart.load([
    // سطح 1: هولدینگ (آبی تیره - Blue 900)
    { 
        id: "1", 
        name: "گروه هولدینگ پارسیان", 
        title: "هولدینگ", 
        tags: ["holding"],
        img: "https://cdn.balkan.app/shared/1.jpg" 
    },
    
    // سطح 2: برندها (آبی روشن - Sky 500)
    { 
        id: "2", 
        pid: "1", 
        name: "برند تکنولوژی پارس", 
        title: "شرکت فناوری", 
        tags: ["brand"],
        img: "https://cdn.balkan.app/shared/2.jpg" 
    },
    { 
        id: "3", 
        pid: "1", 
        name: "برند صنایع نوین", 
        title: "شرکت صنعتی", 
        tags: ["brand"],
        img: "https://cdn.balkan.app/shared/3.jpg" 
    },
    { 
        id: "4", 
        pid: "1", 
        name: "برند بازرگانی آریا", 
        title: "شرکت تجاری", 
        tags: ["brand"],
        img: "https://cdn.balkan.app/shared/4.jpg" 
    },
    
    // سطح 3: دپارتمان‌ها (سبز - Emerald 600)
    // دپارتمان‌های برند تکنولوژی
    { 
        id: "5", 
        pid: "2", 
        name: "دپارتمان توسعه نرم‌افزار", 
        title: "واحد فنی", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/5.jpg" 
    },
    { 
        id: "6", 
        pid: "2", 
        name: "دپارتمان تضمین کیفیت", 
        title: "واحد QA", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/6.jpg" 
    },
    
    // دپارتمان‌های برند صنایع
    { 
        id: "7", 
        pid: "3", 
        name: "دپارتمان تولید", 
        title: "واحد ساخت", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/7.jpg" 
    },
    { 
        id: "8", 
        pid: "3", 
        name: "دپارتمان کنترل کیفیت", 
        title: "واحد بازرسی", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/8.jpg" 
    },
    
    // دپارتمان‌های برند بازرگانی
    { 
        id: "9", 
        pid: "4", 
        name: "دپارتمان فروش", 
        title: "واحد تجاری", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/9.jpg" 
    },
    { 
        id: "10", 
        pid: "4", 
        name: "دپارتمان بازاریابی", 
        title: "واحد تبلیغات", 
        tags: ["department"],
        img: "https://cdn.balkan.app/shared/10.jpg" 
    },

    // سطح 4: تیم‌ها (نارنجی - Amber 600)
    // تیم‌های دپارتمان توسعه نرم‌افزار
    { 
        id: "11", 
        pid: "5", 
        name: "تیم فرانت‌اند", 
        title: "تیم رابط کاربری", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/11.jpg" 
    },
    { 
        id: "12", 
        pid: "5", 
        name: "تیم بک‌اند", 
        title: "تیم زیرساخت", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/12.jpg" 
    },
    
    // تیم‌های دپارتمان QA
    { 
        id: "13", 
        pid: "6", 
        name: "تیم تست اتوماتیک", 
        title: "تیم خودکارسازی", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/13.jpg" 
    },
    { 
        id: "14", 
        pid: "6", 
        name: "تیم تست دستی", 
        title: "تیم بازرسی کیفیت", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/14.jpg" 
    },
    
    // تیم‌های دپارتمان تولید
    { 
        id: "15", 
        pid: "7", 
        name: "تیم خط تولید A", 
        title: "واحد تولید اول", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/15.jpg" 
    },
    { 
        id: "16", 
        pid: "7", 
        name: "تیم خط تولید B", 
        title: "واحد تولید دوم", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/16.jpg" 
    },
    
    // تیم‌های دپارتمان کنترل کیفیت
    { 
        id: "17", 
        pid: "8", 
        name: "تیم بازرسی ورودی", 
        title: "کنترل مواد اولیه", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/1.jpg" 
    },
    { 
        id: "18", 
        pid: "8", 
        name: "تیم بازرسی نهایی", 
        title: "کنترل محصول", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/2.jpg" 
    },
    
    // تیم‌های دپارتمان فروش
    { 
        id: "19", 
        pid: "9", 
        name: "تیم فروش داخلی", 
        title: "فروش کشوری", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/3.jpg" 
    },
    { 
        id: "20", 
        pid: "9", 
        name: "تیم فروش بین‌المللی", 
        title: "صادرات", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/4.jpg" 
    },
    
    // تیم‌های دپارتمان بازاریابی
    { 
        id: "21", 
        pid: "10", 
        name: "تیم دیجیتال مارکتینگ", 
        title: "بازاریابی آنلاین", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/5.jpg" 
    },
    { 
        id: "22", 
        pid: "10", 
        name: "تیم برندینگ", 
        title: "مدیریت برند", 
        tags: ["team"],
        img: "https://cdn.balkan.app/shared/6.jpg" 
    },
    
    // سطح 5: کارکنان (بنفش - Violet 600)
    // کارکنان تیم فرانت‌اند
    { 
        id: "101", 
        pid: "11", 
        name: "علی احمدی", 
        title: "توسعه‌دهنده ارشد", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/7.jpg" 
    },
    { 
        id: "102", 
        pid: "11", 
        name: "زهرا کریمی", 
        title: "توسعه‌دهنده فرانت‌اند", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/8.jpg" 
    },
    
    // کارکنان تیم بک‌اند
    { 
        id: "103", 
        pid: "12", 
        name: "رضا محمدی", 
        title: "توسعه‌دهنده بک‌اند", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/9.jpg" 
    },
    { 
        id: "104", 
        pid: "12", 
        name: "مریم نوری", 
        title: "مهندس دیتابیس", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/10.jpg" 
    },
    
    // کارکنان تیم تست اتوماتیک
    { 
        id: "105", 
        pid: "13", 
        name: "محمد رضایی", 
        title: "تحلیلگر کیفیت", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/11.jpg" 
    },
    { 
        id: "106", 
        pid: "13", 
        name: "فاطمه محمدی", 
        title: "تستر اتوماسیون", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/12.jpg" 
    },
    
    // کارکنان تیم تست دستی
    { 
        id: "107", 
        pid: "14", 
        name: "حسین علوی", 
        title: "تستر نرم‌افزار", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/13.jpg" 
    },
    { 
        id: "108", 
        pid: "14", 
        name: "سارا حسینی", 
        title: "کارشناس QA", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/14.jpg" 
    },
    
    // کارکنان تیم خط تولید A
    { 
        id: "109", 
        pid: "15", 
        name: "امیر جعفری", 
        title: "سرپرست خط تولید", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/15.jpg" 
    },
    { 
        id: "110", 
        pid: "15", 
        name: "نرگس مرادی", 
        title: "اپراتور تولید", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/16.jpg" 
    },
    
    // کارکنان تیم خط تولید B
    { 
        id: "111", 
        pid: "16", 
        name: "پوریا کاظمی", 
        title: "سرپرست شیفت", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/1.jpg" 
    },
    { 
        id: "112", 
        pid: "16", 
        name: "لیلا صادقی", 
        title: "تکنسین تولید", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/2.jpg" 
    },
    
    // کارکنان تیم بازرسی ورودی
    { 
        id: "113", 
        pid: "17", 
        name: "مهدی باقری", 
        title: "بازرس کیفیت", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/3.jpg" 
    },
    { 
        id: "114", 
        pid: "17", 
        name: "آرزو فتحی", 
        title: "کارشناس آزمایشگاه", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/4.jpg" 
    },
    
    // کارکنان تیم بازرسی نهایی
    { 
        id: "115", 
        pid: "18", 
        name: "سعید رحمانی", 
        title: "تحلیلگر فنی", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/5.jpg" 
    },
    { 
        id: "116", 
        pid: "18", 
        name: "ساناز موسوی", 
        title: "بازرس کنترل کیفیت", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/6.jpg" 
    },
    
    // کارکنان تیم فروش داخلی
    { 
        id: "117", 
        pid: "19", 
        name: "رامین احمدی", 
        title: "مدیر فروش", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/7.jpg" 
    },
    { 
        id: "118", 
        pid: "19", 
        name: "شیدا کریمی", 
        title: "کارشناس فروش", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/8.jpg" 
    },
    
    // کارکنان تیم فروش بین‌المللی
    { 
        id: "119", 
        pid: "20", 
        name: "بهزاد نصیری", 
        title: "مدیر صادرات", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/9.jpg" 
    },
    { 
        id: "120", 
        pid: "20", 
        name: "نیلوفر رضوی", 
        title: "کارشناس بازرگانی", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/10.jpg" 
    },
    
    // کارکنان تیم دیجیتال مارکتینگ
    { 
        id: "121", 
        pid: "21", 
        name: "کامیار اسدی", 
        title: "متخصص SEO", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/11.jpg" 
    },
    { 
        id: "122", 
        pid: "21", 
        name: "نگار جوادی", 
        title: "مدیر محتوا", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/12.jpg" 
    },
    
    // کارکنان تیم برندینگ
    { 
        id: "123", 
        pid: "22", 
        name: "داریوش قاسمی", 
        title: "مدیر برند", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/13.jpg" 
    },
    { 
        id: "124", 
        pid: "22", 
        name: "پریسا امیری", 
        title: "طراح گرافیک", 
        tags: ["employee"],
        img: "https://cdn.balkan.app/shared/14.jpg" 
    }
]);


    
  </script>
  
</body>
</html>
