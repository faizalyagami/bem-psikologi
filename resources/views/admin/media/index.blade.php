@extends('layouts.admin')

@section('title', 'Media')
@section('breadcrumb', 'Media')

@section('content')

    <x-admin.page-header title="Media Manager" description="Kelola gambar, dokumen, dan aset website.">
        <button type="button" @click="$dispatch('open-upload-modal')" class="btn-primary">
            <i data-lucide="upload" class="w-4 h-4"></i>
            Upload File
        </button>

        <button type="button" @click="$dispatch('open-folder-modal')" class="btn-secondary">
            <i data-lucide="folder-plus" class="w-4 h-4"></i>
            Folder Baru
        </button>
    </x-admin.page-header>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

        {{-- SIDEBAR FOLDER --}}
        <div class="lg:col-span-1">
            <div class="admin-card p-4">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                    Folder
                </h3>

                <ul class="space-y-1">
                    {{-- ROOT --}}
                    <li>
                        <a href="{{ route('admin.media.index') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                                {{ !$activeFolder ? 'bg-brand-900/40 text-brand-300' : 'text-gray-400 hover:bg-dark-hover' }}">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Root
                        </a>
                    </li>

                    {{-- FOLDER LIST --}}
                    @foreach ($folders as $folder)
                        <li>
                            <a href="{{ route('admin.media.index', ['folder' => $folder->id]) }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                                    {{ $activeFolder?->id === $folder->id ? 'bg-brand-900/40 text-brand-300' : 'text-gray-400 hover:bg-dark-hover' }}">
                                <i data-lucide="folder" class="w-4 h-4 text-accent"></i>
                                <span class="truncate">{{ $folder->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- BREADCRUMB & SEARCH --}}
            <div class="admin-card p-4 flex flex-col sm:flex-row gap-3 items-center justify-between">

                <div class="flex items-center gap-2 text-sm text-gray-400 flex-wrap">
                    <a href="{{ route('admin.media.index') }}" class="hover:text-white transition">
                        Root
                    </a>

                    @foreach ($breadcrumbs as $breadcrumb)
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>

                        <a href="{{ route('admin.media.index', ['folder' => $breadcrumb->id]) }}"
                            class="hover:text-white transition">
                            {{ $breadcrumb->name }}
                        </a>
                    @endforeach

                    {{-- HAPUS FOLDER AKTIF --}}
                    @if ($activeFolder)
                        <form action="{{ route('admin.media.folders.destroy', $activeFolder) }}" method="POST"
                            class="ml-2" onsubmit="return confirm('Hapus folder beserta isinya?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                Hapus Folder
                            </button>
                        </form>
                    @endif
                </div>

                {{-- SEARCH --}}
                <form method="GET" action="{{ route('admin.media.index') }}" class="flex gap-2 w-full sm:w-auto">
                    @if ($activeFolder)
                        <input type="hidden" name="folder" value="{{ $activeFolder->id }}">
                    @endif

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari file..."
                        class="admin-input text-sm w-full sm:w-56">

                    <button type="submit" class="btn-secondary">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            {{-- SUBFOLDER --}}
            @if ($subFolders->count())
                <div class="admin-card p-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                        Subfolder
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($subFolders as $subFolder)
                            <a href="{{ route('admin.media.index', ['folder' => $subFolder->id]) }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-dark-surface border border-dark-border hover:border-brand-600 text-sm transition">
                                <i data-lucide="folder" class="w-4 h-4 text-accent shrink-0"></i>

                                <span class="text-gray-200 truncate">
                                    {{ $subFolder->name }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- GRID FILE --}}
            <div class="admin-card p-4">

                @if ($files->count())

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-white">
                            Daftar File
                        </h3>

                        <span class="text-xs text-gray-500">
                            {{ $files->total() }} file
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">

                        @foreach ($files as $file)
                            <div class="group relative bg-dark-surface border border-dark-border rounded-lg overflow-hidden hover:border-brand-600 transition"
                                x-data="{ previewOpen: false }">

                                {{-- PREVIEW THUMBNAIL --}}
                                <div class="w-full h-28 bg-dark-bg flex items-center justify-center overflow-hidden cursor-pointer"
                                    @click="previewOpen = true">
                                    @if ($file->is_image)
                                        <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                            loading="lazy">
                                    @else
                                        <div class="flex flex-col items-center gap-2 text-gray-500">
                                            <i data-lucide="file" class="w-10 h-10"></i>
                                            <span class="text-[10px] uppercase">
                                                {{ pathinfo($file->name, PATHINFO_EXTENSION) ?: 'FILE' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- FILE INFORMATION --}}
                                <div class="p-2">
                                    <p class="text-xs text-gray-300 truncate" title="{{ $file->name }}">
                                        {{ $file->name }}
                                    </p>

                                    <p class="text-[10px] text-gray-500 mt-1">
                                        {{ number_format($file->size / 1024, 1) }} KB
                                    </p>
                                </div>

                                {{-- FILE ACTIONS --}}
                                <div class="absolute top-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition">

                                    {{-- COPY URL --}}
                                    <button type="button"
                                        @click.stop="navigator.clipboard.writeText(@js($file->url)).then(() => alert('URL berhasil disalin!')).catch(() => alert('Gagal menyalin URL.'))"
                                        class="p-1.5 rounded bg-black/70 text-white hover:bg-black" title="Salin URL">
                                        <i data-lucide="link" class="w-3.5 h-3.5"></i>
                                    </button>

                                    {{-- DELETE FILE --}}
                                    <form action="{{ route('admin.media.destroy', $file) }}" method="POST"
                                        onsubmit="return confirm('Hapus file ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="p-1.5 rounded bg-black/70 text-white hover:bg-red-700"
                                            title="Hapus file">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- PREVIEW MODAL --}}
                                <div x-show="previewOpen" x-cloak x-transition.opacity @click.self="previewOpen = false"
                                    @keydown.escape.window="previewOpen = false"
                                    class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4">
                                    <div class="relative max-w-4xl w-full max-h-full flex flex-col items-center">

                                        {{-- CLOSE BUTTON --}}
                                        <button type="button" @click="previewOpen = false"
                                            class="absolute -top-10 right-0 text-white hover:text-red-400">
                                            <i data-lucide="x" class="w-7 h-7"></i>
                                        </button>

                                        {{-- IMAGE PREVIEW --}}
                                        @if ($file->is_image)
                                            <img src="{{ $file->url }}" alt="{{ $file->name }}"
                                                class="max-w-full max-h-[75vh] object-contain rounded-lg">
                                        @else
                                            <div class="admin-card p-8 text-center w-full max-w-md">
                                                <i data-lucide="file" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>

                                                <h3 class="text-white font-semibold break-all">
                                                    {{ $file->name }}
                                                </h3>

                                                <p class="text-sm text-gray-400 mt-2">
                                                    {{ number_format($file->size / 1024, 1) }} KB
                                                </p>

                                                <a href="{{ $file->url }}" target="_blank" rel="noopener noreferrer"
                                                    class="btn-primary inline-flex mt-5">
                                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                                    Buka File
                                                </a>
                                            </div>
                                        @endif

                                        <p class="text-sm text-gray-300 mt-3 text-center break-all">
                                            {{ $file->name }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4">
                        {{ $files->withQueryString()->links() }}
                    </div>
                @else
                    {{-- EMPTY STATE --}}
                    <div class="py-12 text-center text-gray-500">
                        <i data-lucide="image-off" class="w-12 h-12 mx-auto mb-2 text-gray-700"></i>

                        <p class="text-sm">
                            Belum ada file di folder ini.
                        </p>

                        <button type="button" @click="$dispatch('open-upload-modal')"
                            class="text-brand-400 hover:text-brand-300 text-sm mt-3">
                            Upload file sekarang
                        </button>
                    </div>

                @endif

            </div>

        </div>
    </div>

    {{-- MODAL UPLOAD --}}

    {{-- MODAL UPLOAD --}}
    <div x-data="{ open: false }" @open-upload-modal.window="open = true">
        <template x-teleport="body">
            <div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
                @click.self="open = false"
                class="fixed inset-0 z-[9999] bg-black/70 flex items-center justify-center p-4">
                <div class="admin-card p-6 w-full max-w-lg shadow-2xl" @click.outside="open = false">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-heading font-semibold text-white">
                            Upload File
                        </h3>

                        <button type="button" @click="open = false" class="text-gray-400 hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf

                        <input type="hidden" name="folder_id" value="{{ $activeFolder?->id }}">

                        <div>
                            <label class="admin-label">
                                Pilih File
                                <span class="text-red-400">*</span>
                            </label>

                            <input type="file" name="files[]" multiple required class="admin-input text-xs">

                            <p class="text-xs text-gray-500 mt-2">
                                Anda dapat memilih lebih dari satu file.
                            </p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="open = false" class="btn-secondary">
                                Batal
                            </button>

                            <button type="submit" class="btn-primary">
                                <i data-lucide="upload" class="w-4 h-4"></i>
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- MODAL FOLDER BARU --}}
    <div x-data="{ open: false }" @open-folder-modal.window="open = true">
        <template x-teleport="body">
            <div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
                @click.self="open = false"
                class="fixed inset-0 z-[9999] bg-black/70 flex items-center justify-center p-4">
                <div class="admin-card p-6 w-full max-w-md shadow-2xl" @click.outside="open = false">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-heading font-semibold text-white">
                            Folder Baru
                        </h3>

                        <button type="button" @click="open = false" class="text-gray-400 hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.media.folders.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <input type="hidden" name="parent_id" value="{{ $activeFolder?->id }}">

                        <div>
                            <label class="admin-label">
                                Nama Folder
                                <span class="text-red-400">*</span>
                            </label>

                            <input type="text" name="name" required maxlength="100" class="admin-input"
                                placeholder="Misal: Seminar 2026">
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="open = false" class="btn-secondary">
                                Batal
                            </button>

                            <button type="submit" class="btn-primary">
                                <i data-lucide="folder-plus" class="w-4 h-4"></i>
                                Buat Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>


@endsection
