<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $pageTitle ?? 'چارت سازمانی' }} | Planexx</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite([
      'Applications/AdminPanel/Resources/css/app.css',
      'Applications/AdminPanel/Resources/js/pages/organization-chart.js'
  ])

  <style>
    body, button, input, textarea, select, p, div, span, h1, h2, h3, h4, h5, h6 {
      font-family: 'Sahel', sans-serif !important;
    }

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

    .boc-link {
      z-index: 0 !important;
    }

    .boc-node {
      z-index: 1 !important;
    }

    .boc-expander-icon {
      background: #9ca3af !important;
      opacity: 0.6 !important;
    }

    .boc-expander-icon:hover {
      opacity: 0.9 !important;
    }

    [data-boc-edit-form] {
        display: none !important;
    }
  </style>
</head>
<body style="background-color: #f8fafc; margin: 0; padding: 0;">

  <div id="tree"></div>

  <div id="info-sidebar" style="position: fixed; top: 0; right: -400px; width: 400px; height: 100%; background: white; box-shadow: -4px 0 24px rgba(0, 0, 0, 0.12); transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 100; overflow-y: auto;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
      <h3 id="sidebar-title" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: #0f172a;">اطلاعات</h3>
      <button id="close-sidebar" style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #f8fafc; border: none; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
        <i class="fa-solid fa-xmark" style="font-size: 1.25rem;"></i>
      </button>
    </div>

    <div id="sidebar-content" style="padding: 1.5rem;">
    </div>
  </div>

  <div style="position: fixed; top: 1.5rem; left: 1.5rem; z-index: 50;">
    <a href="{{ route('web.org.departments.index') }}" style="padding: 0 1rem; height: 3rem; background: white; color: #0f172a; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: 1px solid #f1f5f9; text-decoration: none; transition: all 0.2s;">
      <span style="font-size: 0.875rem; font-weight: 500;">برگشت</span>
      <i class="fa-solid fa-arrow-left" style="font-size: 1.125rem;"></i>
    </a>
  </div>

  <div style="position: fixed; bottom: 1.5rem; left: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem; z-index: 50;">
    <button id="export-pdf" style="width: 3rem; height: 3rem; background: #0f172a; color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: none;">
      <i class="fa-solid fa-file-pdf" style="font-size: 1.125rem;"></i>
    </button>

    <button id="export-svg" style="width: 3rem; height: 3rem; background: #0f172a; color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: none;">
      <i class="fa-solid fa-file-image" style="font-size: 1.125rem;"></i>
    </button>

    <button id="zoom-in" style="width: 3rem; height: 3rem; background: #0f172a; color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: none;">
      <i class="fa-solid fa-magnifying-glass-plus" style="font-size: 1.125rem;"></i>
    </button>

    <button id="zoom-out" style="width: 3rem; height: 3rem; background: #0f172a; color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: none;">
      <i class="fa-solid fa-magnifying-glass-minus" style="font-size: 1.125rem;"></i>
    </button>

    <button id="fit-screen" style="width: 3rem; height: 3rem; background: #0f172a; color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: none;">
      <i class="fa-solid fa-compress" style="font-size: 1.125rem;"></i>
    </button>
  </div>

  <script>
    window.departmentsData = @json($departments);
  </script>

</body>
</html>
