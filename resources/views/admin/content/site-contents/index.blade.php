@extends('layouts.admin')
@section('title', 'Konten Situs')
@section('breadcrumb', 'Konten Situs')

@section('content')

    <x-admin.page-header title="Konten Situs"
        description="Kelola teks, gambar, dan bagian-bagian homepage."></x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.site-contents.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        @foreach ($contents as $group => $items)
            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-4 capitalize border-b border-dark-border pb-2">
                    {{ str_replace('_', ' ', $group) }}
                </h3>
                <div class="space-y-4">
                    @foreach ($items as $item)
                        <div>
                            <label class="admin-label">{{ $item->label }}</label>

                            @if ($item->type === 'text')
                                <input type="text" name="{{ $item->key }}" value="{{ old($item->key, $item->value) }}"
                                    class="admin-input">
                            @elseif($item->type === 'textarea')
                                <textarea name="{{ $item->key }}" rows="3" class="admin-input">{{ old($item->key, $item->value) }}</textarea>
                            @elseif($item->type === 'richtext')
                                <textarea name="{{ $item->key }}" rows="6" class="admin-input">{{ old($item->key, $item->value) }}</textarea>
                            @elseif($item->type === 'image')
                                @if ($item->value)
                                    <img src="{{ Storage::url($item->value) }}"
                                        class="h-24 mb-2 rounded border border-dark-border">
                                @endif
                                <input type="file" name="{{ $item->key }}" accept="image/*"
                                    class="admin-input text-xs">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan Semua
                Konten</button>
        </div>
    </form>

@endsection
