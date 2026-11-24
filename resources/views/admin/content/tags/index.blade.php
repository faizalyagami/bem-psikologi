@extends('layouts.admin')
@section('title', 'Tag')
@section('breadcrumb', 'Tag')

@section('content')

    <x-admin.page-header title="Manajemen Tag" description="Kelola label bebas untuk berita dan artikel.">
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Tambah --}}
        <div class="admin-card p-5 h-fit">
            <h3 class="font-heading font-semibold text-white mb-4">Tambah Tag Baru</h3>
            <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="admin-label">Nama Tag</label>
                    <input type="text" name="name" required class="admin-input" placeholder="Misal: Psikologi">
                </div>
                <div>
                    <label class="admin-label">Slug <span class="text-gray-500 font-normal">(opsional)</span></label>
                    <input type="text" name="slug" class="admin-input font-mono text-xs" placeholder="psikologi">
                </div>
                <button type="submit" class="btn-primary w-full justify-center">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tag
                </button>
            </form>
        </div>

        {{-- List Tag --}}
        <div class="lg:col-span-2 admin-card">
            <div class="p-5 border-b border-dark-border">
                <h3 class="font-heading font-semibold text-white">Daftar Tag ({{ $tags->total() }})</h3>
            </div>
            <div class="divide-y divide-dark-border">
                @forelse($tags as $tag)
                    <div class="p-4 flex items-center justify-between hover:bg-dark-hover transition"
                        x-data="{ editing: false }">
                        <div x-show="!editing" class="flex items-center gap-3 flex-1">
                            <span class="text-sm font-medium text-white">#{{ $tag->name }}</span>
                            <span class="text-xs text-gray-500 font-mono">{{ $tag->slug }}</span>
                            <span
                                class="text-[10px] px-2 py-0.5 rounded-full bg-dark-surface text-gray-400">{{ $tag->posts_count }}
                                pos</span>
                        </div>
                        <form x-show="editing" x-cloak action="{{ route('admin.tags.update', $tag) }}" method="POST"
                            class="flex-1 flex gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $tag->name }}" class="admin-input" required>
                            <button type="submit" class="btn-primary px-3"><i data-lucide="check"
                                    class="w-4 h-4"></i></button>
                            <button type="button" @click="editing = false" class="btn-secondary px-3"><i data-lucide="x"
                                    class="w-4 h-4"></i></button>
                        </form>
                        <div x-show="!editing" class="flex items-center gap-1">
                            <button @click="editing = true"
                                class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST"
                                onsubmit="return confirm('Hapus tag #{{ $tag->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">Belum ada tag.</div>
                @endforelse
            </div>
            @if ($tags->hasPages())
                <div class="px-4 py-3 border-t border-dark-border">{{ $tags->links() }}</div>
            @endif
        </div>
    </div>

@endsection
