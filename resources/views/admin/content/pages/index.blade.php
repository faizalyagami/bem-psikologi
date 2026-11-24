@extends('layouts.admin')
@section('title', 'Halaman')
@section('breadcrumb', 'Halaman')

@section('content')

    <x-admin.page-header title="Manajemen Halaman" description="Kelola halaman statis seperti Tentang BEM, Visi Misi, dll.">
        <a href="{{ route('admin.pages.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Halaman
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul halaman..."
                    class="admin-input pl-9">
            </div>
            <select name="status" class="admin-input sm:w-40">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.pages.index') }}" class="btn-secondary"><i data-lucide="x" class="w-4 h-4"></i>
                    Reset</a>
            @endif
        </form>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Diperbarui</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($pages as $page)
                        @php $tr = $page->translation('id'); @endphp
                        <tr class="hover:bg-dark-hover">
                            <td class="px-4 py-3 font-medium text-white">{{ $tr?->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $tr?->slug }}</td>
                            <td class="px-4 py-3">
                                @php $badge = $page->status === 'published' ? 'bg-green-900/30 text-green-400' : 'bg-yellow-900/30 text-yellow-400'; @endphp
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-full {{ $badge }}">{{ ucfirst($page->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $page->updated_at?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.pages.edit', $page) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400"
                                        title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                        onsubmit="return confirm('Hapus halaman ini?')">
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
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                                <i data-lucide="files" class="w-12 h-12 mx-auto mb-2 text-gray-700"></i>
                                <p>Belum ada halaman. Klik "Tambah Halaman" untuk mulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pages->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $pages->links() }}</div>
        @endif
    </div>

@endsection
