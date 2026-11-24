@extends('layouts.admin')
@section('title', 'Pos / Berita')
@section('breadcrumb', 'Pos')

@section('content')

    <x-admin.page-header title="Manajemen Pos & Berita" description="Kelola berita, artikel, dan pengumuman.">
        <a href="{{ route('admin.posts.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tulis Berita
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..."
                    class="admin-input pl-9">
            </div>
            <select name="status" class="admin-input sm:w-40">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="archived" @selected(request('status') === 'archived')>Archived</option>
            </select>
            <select name="category" class="admin-input sm:w-48">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary"><i data-lucide="x" class="w-4 h-4"></i>
                    Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Penulis</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($posts as $post)
                        @php $tr = $post->translation('id'); @endphp
                        <tr class="hover:bg-dark-hover">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}"
                                            class="w-10 h-10 rounded object-cover border border-dark-border" alt="">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded bg-dark-surface border border-dark-border flex items-center justify-center">
                                            <i data-lucide="image" class="w-4 h-4 text-gray-600"></i>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-white truncate">{{ $tr?->title ?? '—' }}</p>
                                        <p class="text-xs text-gray-500 font-mono truncate">{{ $tr?->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-400">{{ $post->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match ($post->status) {
                                        'published' => 'bg-green-900/30 text-green-400',
                                        'draft' => 'bg-yellow-900/30 text-yellow-400',
                                        'archived' => 'bg-gray-800 text-gray-400',
                                    };
                                @endphp
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-full {{ $badge }}">{{ ucfirst($post->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-400">{{ $post->user?->name }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $post->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400"
                                        title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                        onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-2 text-gray-700"></i>
                                <p>Belum ada berita. Klik "Tulis Berita" untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($posts->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $posts->links() }}</div>
        @endif
    </div>

@endsection
