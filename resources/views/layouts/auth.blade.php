<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') - BEM Psikologi CMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-dark-bg text-gray-300 selection:bg-brand-600 selection:text-white">

    <div class="min-h-screen flex">
        
        {{-- KIRI: Branding / Visual --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-brand-900 to-dark-bg border-r border-dark-border">
            {{-- Ornamen --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full bg-accent blur-3xl"></div>
                <div class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full bg-brand-500 blur-3xl"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                {{-- Logo --}}
                <a href="{{ route('home', ['locale' => 'id']) }}" class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-800 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        Ψ
                    </div>
                    <div>
                        <h1 class="text-lg font-heading font-bold text-white tracking-wide">BEM PSIKOLOGI</h1>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Content Management System</p>
                    </div>
                </a>

                {{-- Quote --}}
                <div>
                    <h2 class="font-heading text-4xl font-bold text-white leading-tight mb-6">
                        Kelola Website<br>
                        <span class="text-accent">Lebih Mudah</span><br>
                        & Terpusat.
                    </h2>
                    <p class="text-gray-400 leading-relaxed max-w-md">
                        Sistem Manajemen Konten resmi BEM Fakultas Psikologi Unisba. Kelola berita, agenda, galeri, dan aspirasi mahasiswa dalam satu platform.
                    </p>
                </div>

                {{-- Footer --}}
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} BEM Fakultas Psikologi Unisba. Hak cipta dilindungi.
                </p>
            </div>
        </div>

        {{-- KANAN: Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
            {{-- Mobile Logo --}}
            <div class="absolute top-8 left-6 lg:hidden">
                <a href="{{ route('home', ['locale' => 'id']) }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-lg bg-brand-800 flex items-center justify-center text-white font-bold text-xl">Ψ</div>
                    <span class="text-sm font-heading font-bold text-white">BEM Psikologi</span>
                </a>
            </div>

            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>