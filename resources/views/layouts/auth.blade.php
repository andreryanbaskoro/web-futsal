<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Auth' }} | Futsal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/8ba56f53d6.js" crossorigin="anonymous"></script>

    <!-- Dark mode init (tanpa sidebar store) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            }
        })();
    </script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">

    <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        {{-- Logo --}}
        <div class="text-center mb-6">
            <img src="/images/logo/logo.svg" class="mx-auto h-10 dark:hidden">
            <img src="/images/logo/logo-dark.svg" class="mx-auto h-10 hidden dark:block">
        </div>

        {{-- Content --}}
        @yield('content')
    </div>

</body>

</html>