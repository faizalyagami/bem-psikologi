@extends('layouts.admin')
@section('title', 'Kamus / Terjemahan')
@section('breadcrumb', 'Kamus / Terjemahan')

@section('content')

    <x-admin.page-header title="Kamus / Terjemahan"
        description="Kelola entri terjemahan antarmuka dalam bahasa Indonesia & Inggris."></x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah --}}
    <div class="admin-card p-5 mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center gap-2 text-white font-heading font-semibold w-full">
            <i data-lucide="plus-circle" class="w-5 h-5 text-brand-400"></i> Tambah / Update Terjemahan
            <i data-lucide="chevron-down" class="w-4 h-4 ml-auto" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <form x-show="open" x-cloak action="{{ route('admin.translations.store') }}" method="POST"
            class="mt-4 pt-4 border-t border-dark-border grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            <div>
                <label class="admin-label">Grup</label>
                <input type="text" name="group" value="general" required class="admin-input">
            </div>
            <div>
                <label class="admin-label">Key</label>
                <input type="text" name="key" required class="admin-input font-mono text-xs"
                    placeholder="welcome.title">
            </div>
            <div>
                <label class="admin-label">Bahasa</label>
                <select name="locale" class="admin-input">
                    <option value="id">🇮🇩 Indonesia</option>
                    <option value="en">🇬🇧 English</option>
                </select>
            </div>
            <div>
                <label class="admin-label">Nilai</label>
                <input type="text" name="value" required class="admin-input" placeholder="Selamat datang">
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
            </div>
        </form>
    </div>

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari key atau nilai..."
                    class="admin-input pl-9">
            </div>
            <select name="group" class="admin-input sm:w-48">
                <option value="">Semua Grup</option>
                @foreach ($groups as $g)
                    <option value="{{ $g }}" @selected(request('group') === $g)>{{ $g }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="admin-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-dark-surface border-b border-dark-border">
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase">
                    <th class="px-4 py-3">Grup</th>
                    <th class="px-4 py-3">Key</th>
                    <th class="px-4 py-3">Bahasa</th>
                    <th class="px-4 py-3">Nilai</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @forelse($translations as $t)
                    <tr class="hover:bg-dark-hover">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $t->group }}</td>
                        <td class="px-4 py-3 text-white font-mono text-xs">{{ $t->key }}</td>
                        <td class="px-4 py-3">
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-brand-900/30 text-brand-300">
                                {{ $t->locale === 'id' ? '🇮🇩 ID' : '🇬🇧 EN' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">{{ Str::limit($t->value, 60) }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.translations.destroy', $t) }}" method="POST"
                                onsubmit="return confirm('Hapus terjemahan ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada terjemahan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($translations->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $translations->links() }}</div>
        @endif
    </div>

@endsection
