@extends('layouts.admin')
@section('title', 'Menu')
@section('breadcrumb', 'Menu')

@section('content')

    <x-admin.page-header title="Manajemen Menu"
        description="Kelola navigasi header & footer secara dinamis."></x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            {{ session('error') }}</div>
    @endif

    {{-- Form Tambah --}}
    <div class="admin-card p-5 mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center gap-2 text-white font-heading font-semibold w-full">
            <i data-lucide="plus-circle" class="w-5 h-5 text-brand-400"></i> Tambah Menu Baru
            <i data-lucide="chevron-down" class="w-4 h-4 ml-auto" :class="open ? 'rotate-180' : ''"></i>
        </button>

        <form x-show="open" x-cloak action="{{ route('admin.menus.store') }}" method="POST"
            class="mt-4 pt-4 border-t border-dark-border grid grid-cols-1 md:grid-cols-2 gap-3">
            @csrf
            <div>
                <label class="admin-label">Judul</label>
                <input type="text" name="title" required class="admin-input">
            </div>
            <div>
                <label class="admin-label">Induk (opsional)</label>
                <select name="parent_id" class="admin-input">
                    <option value="">— Tanpa induk —</option>
                    @foreach ($menus as $m)
                        <option value="{{ $m->id }}">{{ $m->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="admin-label">Route Name (opsional)</label>
                <input type="text" name="route_name" class="admin-input font-mono text-xs"
                    placeholder="frontend.contact">
            </div>
            <div>
                <label class="admin-label">URL Manual (opsional)</label>
                <input type="text" name="url" class="admin-input font-mono text-xs" placeholder="/id/visi-misi">
            </div>
            <div>
                <label class="admin-label">Lokasi</label>
                <select name="location" class="admin-input">
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                </select>
            </div>
            <div>
                <label class="admin-label">Target</label>
                <select name="target" class="admin-input">
                    <option value="_self">Same Tab</option>
                    <option value="_blank">New Tab</option>
                </select>
            </div>
            <div>
                <label class="admin-label">Urutan</label>
                <input type="number" name="sort_order" value="0" class="admin-input">
            </div>
            <div class="flex items-end gap-3">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" checked
                        class="rounded bg-dark-bg border-dark-border text-brand-600">
                    Aktif
                </label>
                <button type="submit" class="btn-primary ml-auto"><i data-lucide="save" class="w-4 h-4"></i>
                    Simpan</button>
            </div>
        </form>
    </div>

    {{-- List Menu --}}
    <div class="admin-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-dark-surface border-b border-dark-border">
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase">
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Tujuan</th>
                    <th class="px-4 py-3">Lokasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @forelse($menus as $menu)
                    @include('admin.management.menus._row', ['menu' => $menu, 'level' => 0])
                    @foreach ($menu->children as $child)
                        @include('admin.management.menus._row', ['menu' => $child, 'level' => 1])
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada menu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
