@php
    $menus = [
        ['title' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard'],
        [
            'title' => 'Media',
            'icon' => 'folder',
            'children' => [['title' => 'File Manager', 'route' => 'admin.media.index']],
        ],
    ];
@endphp

<aside x-cloak
    class="absolute inset-y-0 left-0 z-50 w-64 bg-dark-bg border-r border-dark-border transition-transform duration-300 transform lg:static lg:translate-x-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-dark-border shrink-0">
        <div
            class="w-10 h-10 rounded-full bg-brand-800 flex items-center justify-center text-white font-bold text-xl shrink-0">
            Ψ
        </div>
        <div class="min-w-0">
            <h1 class="text-sm font-heading font-bold text-white tracking-wide truncate">BEM PSIKOLOGI</h1>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider truncate">Content Management System</p>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto dark-scroll">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span>Dashboard</span>
        </a>

        {{-- Media --}}
        <a href="{{ route('admin.media.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.media.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="folder" class="w-5 h-5 shrink-0"></i>
            <span>Media</span>
        </a>

        {{-- GROUP: Manajemen --}}
        <div class="sidebar-group-label">Manajemen</div>

        @can('manage-users')
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'sidebar-item-active' : '' }}">
                <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                <span>Pengguna</span>
            </a>
        @endcan

        @can('manage-roles')
            <a href="{{ route('admin.roles.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'sidebar-item-active' : '' }}">
                <i data-lucide="shield-check" class="w-5 h-5 shrink-0"></i>
                <span>Peran & Izin Akses</span>
            </a>
        @endcan
        <a href="{{ route('admin.menus.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.menus.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="menu" class="w-5 h-5 shrink-0"></i>
            <span>Menu</span>
        </a>
        <a href="{{ route('admin.translations.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.translations.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="languages" class="w-5 h-5 shrink-0"></i>
            <span>Kamus / Terjemahan</span>
        </a>
        <a href="{{ route('admin.logs.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.logs.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="file-clock" class="w-5 h-5 shrink-0"></i>
            <span>Log Audit</span>
        </a>

        {{-- GROUP: Konten --}}
        <div class="sidebar-group-label">Konten</div>

        <a href="{{ route('admin.site-contents.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.site-contents.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="layout-template" class="w-5 h-5 shrink-0"></i>
            <span>Konten Situs</span>
        </a>
        <a href="{{ route('admin.posts.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.posts.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="file-text" class="w-5 h-5 shrink-0"></i>
            <span>Pos</span>
        </a>
        <a href="{{ route('admin.pages.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.pages.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="files" class="w-5 h-5 shrink-0"></i>
            <span>Halaman</span>
        </a>
        <a href="{{ route('admin.categories.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="tags" class="w-5 h-5 shrink-0"></i>
            <span>Kategori</span>
        </a>
        <a href="{{ route('admin.tags.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.tags.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="tag" class="w-5 h-5 shrink-0"></i>
            <span>Tag</span>
        </a>
        <a href="{{ route('admin.events.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.events.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="calendar" class="w-5 h-5 shrink-0"></i>
            <span>Agenda</span>
        </a>

        @can('manage-aspirations')
            <a href="{{ route('admin.aspirations.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.aspirations.*') ? 'sidebar-item-active' : '' }}">
                <i data-lucide="message-square" class="w-5 h-5 shrink-0"></i>
                <span>Aspirasi Mahasiswa</span>
            </a>
        @endcan

        {{-- GROUP: Pengaturan --}}
        <div class="sidebar-group-label">Sistem</div>

        <a href="{{ route('admin.settings.index') }}"
            class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'sidebar-item-active' : '' }}">
            <i data-lucide="settings" class="w-5 h-5 shrink-0"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    {{-- BOTTOM --}}
    <div class="px-3 py-3 border-t border-dark-border space-y-0.5 shrink-0">
        <a href="{{ route('home', ['locale' => 'id']) }}" target="_blank" class="sidebar-item">
            <i data-lucide="external-link" class="w-5 h-5"></i>
            <span>Lihat Website</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-900/20 hover:text-red-300 transition">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Overlay mobile --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>
