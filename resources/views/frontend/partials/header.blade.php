@php
    $headerMenus = \App\Models\Menu::with(['children' => fn($q) => $q->where('is_active', true)])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->where('location', 'header')
        ->orderBy('sort_order')
        ->get();
@endphp

<header x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 20"
    class="fixed top-0 inset-x-0 z-40 transition-all duration-300"
    :class="scrolled ? 'bg-white/95 shadow-md backdrop-blur' : 'bg-white/80 backdrop-blur'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-3">
                @if (setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ setting('site_name') }}" class="h-10">
                @else
                    <div
                        class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xl">
                        Ψ</div>
                @endif
                <div class="hidden sm:block">
                    <p class="font-heading font-bold text-primary text-lg leading-tight">
                        {{ setting('site_name', 'BEM Psikologi') }}</p>
                    <p class="text-xs text-gray-500">{{ setting('site_tagline', 'Fakultas Psikologi') }}</p>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                @foreach ($headerMenus as $menu)
                    @if ($menu->children->count())
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary rounded-lg hover:bg-gray-50 transition">
                                {{ $menu->title }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition
                                class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-lg py-2">
                                @foreach ($menu->children as $child)
                                    <a href="{{ menuUrl($child) }}"
                                        class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary">
                                        {{ $child->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ menuUrl($menu) }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary rounded-lg hover:bg-gray-50 transition">
                            {{ $menu->title }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Language & CTA --}}
            <div class="flex items-center gap-2">
                {{-- Language --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-1 px-3 py-2 text-sm text-gray-700 hover:text-primary rounded-lg hover:bg-gray-50">
                        {{ app()->getLocale() === 'id' ? '🇮🇩' : '🇬🇧' }} {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-lg py-1">
                        <a href="{{ route('home', ['locale' => 'id']) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50">🇮🇩 Indonesia</a>
                        <a href="{{ route('home', ['locale' => 'en']) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50">🇬🇧 English</a>
                    </div>
                </div>

                <a href="{{ route('frontend.contact', ['locale' => app()->getLocale()]) }}"
                    class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-opacity-90 transition">
                    Hubungi Kami
                </a>

                {{-- Mobile toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden bg-white border-t border-gray-100">
        <nav class="px-4 py-4 space-y-1">
            @foreach ($headerMenus as $menu)
                @if ($menu->children->count())
                    <div x-data="{ sub: false }">
                        <button @click="sub = !sub"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
                            {{ $menu->title }}
                            <svg class="w-4 h-4" :class="sub ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="sub" x-cloak class="pl-4 mt-1 space-y-1">
                            @foreach ($menu->children as $child)
                                <a href="{{ menuUrl($child) }}"
                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50">{{ $child->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ menuUrl($menu) }}"
                        class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">{{ $menu->title }}</a>
                @endif
            @endforeach
        </nav>
    </div>
</header>
