@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Kategori › Tambah')

@section('content')
    <x-admin.page-header title="Tambah Kategori" description="Buat kategori baru untuk berita.">
        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </x-admin.page-header>

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST"
        class="admin-card p-6 max-w-2xl space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="admin-label">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="admin-input">
        </div>
        <div>
            <label class="admin-label">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                class="admin-input font-mono text-xs">
        </div>
        <div>
            <label class="admin-label">Induk Kategori</label>
            <select name="parent_id" class="admin-input">
                <option value="">— Tidak ada induk —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="admin-label">Deskripsi</label>
            <textarea name="description" rows="3" class="admin-input">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="flex justify-end gap-2 pt-2 border-t border-dark-border">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection
