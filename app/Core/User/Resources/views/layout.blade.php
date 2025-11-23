<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Planexx</title>
    <meta name="description" content="سیستم مدیریت هوشمند پروژه">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Ziggy Routes -->
    @routes

    <!-- Vite Assets - بارگذاری تمام assets از جمله ماژول User -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-bg-secondary min-h-screen flex items-center justify-center p-4">
@yield('content')
</body>

@stack('scripts')

</html>
