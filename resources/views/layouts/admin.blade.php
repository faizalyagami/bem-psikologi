<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — BEM Psikologi CMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: {{ setting('color_primary', '#123C32') }};
            --color-accent: {{ setting('color_accent', '#D4AD58') }};
        }
    </style>

</head>

<body class="bg-dark-bg text-gray-300 font-sans antialiased selection:bg-brand-600 selection:text-white">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        {{-- SIDEBAR --}}
        <x-admin.sidebar />

        {{-- MAIN AREA --}}
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            {{-- TOPBAR --}}
            <x-admin.topbar />

            {{-- PAGE CONTENT --}}
            <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            <footer class="mt-auto px-6 py-4 border-t border-dark-border text-xs text-gray-500 flex justify-between">
                <span>&copy; {{ date('Y') }} BEM Fakultas Psikologi Unisba</span>
                <span>CMS v1.0.0</span>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>

</html>
