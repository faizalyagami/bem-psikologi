<header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-dark-border bg-dark-bg/95 backdrop-blur px-4 sm:px-6 lg:px-8">

    {{-- Mobile menu toggle --}}
    <button type="button" @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden -m-2.5 p-2.5 text-gray-300 hover:text-white hover:bg-dark-hover rounded-lg transition"
        aria-label="Toggle sidebar">
        <i data-lucide="menu" class="h-6 w-6"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex flex-1 items-center gap-x-4 self-stretch">
        <nav class="flex items-center text-sm text-gray-500 space-x-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 flex items-center gap-1.5">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Admin</span>
            </a>
            @hasSection('breadcrumb')
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-600"></i>
                <span class="text-gray-300 font-medium">@yield('breadcrumb')</span>
            @endif
        </nav>
    </div>

    {{-- Right side --}}
    <div class="flex items-center gap-x-3 lg:gap-x-5">

        {{-- Search --}}
        <div class="hidden md:block relative">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input type="text" placeholder="Cari menu atau konten..."
                class="w-64 pl-9 pr-3 py-2 bg-dark-surface border border-dark-border rounded-lg text-sm text-gray-200 placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-900/50 focus:outline-none transition">
        </div>

        {{-- Language switcher --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition px-2 py-1.5 rounded-lg hover:bg-dark-hover">
                <span class="text-lg leading-none">🇮🇩</span>
                <span class="hidden sm:inline font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-40 bg-dark-card border border-dark-border rounded-lg shadow-xl py-1 z-50">
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-white bg-dark-hover">
                    <span>🇮🇩</span> Indonesia
                </a>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover">
                    <span>🇬🇧</span> English
                </a>
            </div>
        </div>

        <div class="h-6 w-px bg-dark-border"></div>

        {{-- Profile --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-x-2.5 text-sm">
                <img class="h-8 w-8 rounded-full border border-dark-border"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=173F35&color=D6B66D"
                    alt="Avatar">
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-white leading-tight">{{ Auth::user()->name ?? 'Administrator' }}
                    </p>
                    <p class="text-xs text-gray-500 leading-tight">
                        {{ Auth::user()->getRoleNames()->first() ?? 'Admin' }}</p>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-500"></i>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-56 bg-dark-card border border-dark-border rounded-lg shadow-xl py-1 z-50">
                <div class="px-4 py-3 border-b border-dark-border">
                    <p class="text-sm text-white font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-dark-hover">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Saya
                </a>
                <div class="border-t border-dark-border my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-900/20">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
