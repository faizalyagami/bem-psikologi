@extends('layouts.admin')
@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')

    <x-admin.page-header title="Manajemen Kategori" description="Kelola kategori untuk berita dan artikel.">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Induk</th>
                        <th class="px-4 py-3">Jumlah Pos</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-dark-hover">
                            <td class="px-4 py-3 font-medium text-white">{{ $cat->name }}</td>
                            <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $cat->slug }}</td>
                            <td class="px-4 py-3 text-gray-400">{{ $cat->parent?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-xs px-2 py-0.5 rounded-full bg-brand-900/30 text-brand-300">{{ $cat->posts_count }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                        onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
