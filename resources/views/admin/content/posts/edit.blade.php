@extends('layouts.admin')
@section('title', 'Edit Berita')
@section('breadcrumb', 'Pos › Edit')

@section('content')

    <x-admin.page-header title="Edit Berita" description="Perbarui berita atau artikel.">
        <a href="{{ route('admin.posts.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </x-admin.page-header>

    @php
        $trId = $post->translations->firstWhere('locale', 'id');
        $trEn = $post->translations->firstWhere('locale', 'en');
        $publishedValue = old('published_at');
        if (!$publishedValue && $post->published_at) {
            $publishedValue = \Illuminate\Support\Carbon::parse($post->published_at)->format('Y-m-d\TH:i');
        }
    @endphp

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ lang: 'id' }">
        @csrf @method('PUT')

        @if ($errors->any())
            <div class="px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ============ KOLOM UTAMA ============ --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- KONTEN MULTI-BAHASA --}}
                <div class="admin-card p-5">
                    {{-- Tab Bahasa --}}
                    <div class="flex gap-2 mb-4 border-b border-dark-border">
                        <button type="button" @click="lang='id'"
                            :class="lang === 'id' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm transition flex items-center gap-2">
                            🇮🇩 Indonesia
                        </button>
                        <button type="button" @click="lang='en'"
                            :class="lang === 'en' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm transition flex items-center gap-2">
                            🇬🇧 English <span class="text-[10px] text-gray-500">(opsional)</span>
                        </button>
                    </div>

                    {{-- ============ FORM ID ============ --}}
                    <div x-show="lang==='id'" class="space-y-4">
                        <div>
                            <label class="admin-label">Judul (ID) <span class="text-red-400">*</span></label>
                            <input type="text" name="id[title]" value="{{ old('id.title', $trId?->title) }}" required
                                class="admin-input" placeholder="Judul berita...">
                        </div>
                        <div>
                            <label class="admin-label">Slug (ID) <span
                                    class="text-gray-500 font-normal">(otomatis)</span></label>
                            <input type="text" name="id[slug]" value="{{ old('id.slug', $trId?->slug) }}"
                                class="admin-input font-mono text-xs" placeholder="judul-berita">
                        </div>
                        <div>
                            <label class="admin-label">Konten (ID) <span class="text-red-400">*</span></label>
                            <textarea name="id[content]" id="content-id" rows="15" class="admin-input">{{ old('id.content', $trId?->content) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Excerpt (ID)</label>
                            <textarea name="id[excerpt]" rows="2" class="admin-input" placeholder="Ringkasan singkat...">{{ old('id.excerpt', $trId?->excerpt) }}</textarea>
                        </div>
                    </div>

                    {{-- ============ FORM EN ============ --}}
                    <div x-show="lang==='en'" x-cloak class="space-y-4">
                        <div>
                            <label class="admin-label">Title (EN)</label>
                            <input type="text" name="en[title]" value="{{ old('en.title', $trEn?->title) }}"
                                class="admin-input" placeholder="News title...">
                        </div>
                        <div>
                            <label class="admin-label">Slug (EN)</label>
                            <input type="text" name="en[slug]" value="{{ old('en.slug', $trEn?->slug) }}"
                                class="admin-input font-mono text-xs">
                        </div>
                        <div>
                            <label class="admin-label">Content (EN)</label>
                            <textarea name="en[content]" id="content-en" rows="15" class="admin-input">{{ old('en.content', $trEn?->content) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Excerpt (EN)</label>
                            <textarea name="en[excerpt]" rows="2" class="admin-input">{{ old('en.excerpt', $trEn?->excerpt) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-4 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4 text-brand-400"></i> SEO Metadata
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label">Meta Title (ID)</label>
                            <input type="text" name="id[meta_title]"
                                value="{{ old('id.meta_title', $trId?->meta_title) }}" class="admin-input" maxlength="255">
                        </div>
                        <div>
                            <label class="admin-label">Meta Title (EN)</label>
                            <input type="text" name="en[meta_title]"
                                value="{{ old('en.meta_title', $trEn?->meta_title) }}" class="admin-input" maxlength="255">
                        </div>
                        <div>
                            <label class="admin-label">Meta Description (ID)</label>
                            <textarea name="id[meta_description]" rows="2" class="admin-input" maxlength="500">{{ old('id.meta_description', $trId?->meta_description) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Meta Description (EN)</label>
                            <textarea name="en[meta_description]" rows="2" class="admin-input" maxlength="500">{{ old('en.meta_description', $trEn?->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ============ SIDEBAR KANAN ============ --}}
            <div class="space-y-5">

                {{-- Publikasi --}}
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-4">Publikasi</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="admin-label">Status</label>
                            <select name="status" class="admin-input" required>
                                <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                                <option value="archived" @selected(old('status', $post->status) === 'archived')>Archived</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Tanggal Publikasi</label>
                            <input type="datetime-local" name="published_at" value="{{ $publishedValue }}"
                                class="admin-input">
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-3">Gambar Utama</h3>
                    @if ($post->featured_image)
                        <div class="mb-3 relative">
                            <img src="{{ Storage::url($post->featured_image) }}"
                                class="w-full h-32 object-cover rounded border border-dark-border">
                            <span
                                class="absolute top-2 right-2 text-[10px] px-2 py-0.5 bg-black/70 text-white rounded">Gambar
                                saat ini</span>
                        </div>
                    @endif
                    <input type="file" name="featured_image" accept="image/*"
                        class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
                    <p class="text-xs text-gray-500 mt-2">
                        {{ $post->featured_image ? 'Biarkan kosong jika tidak ingin mengganti gambar.' : 'JPG, PNG, WebP. Maks 2MB.' }}
                    </p>
                </div>

                {{-- Kategori --}}
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-3">Kategori</h3>
                    <select name="category_id" class="admin-input">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tag --}}
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-3">Tag</h3>
                    <div class="max-h-48 overflow-y-auto dark-scroll space-y-2">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    @checked(in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())))
                                    class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600">
                                <span class="text-gray-300">#{{ $tag->name }}</span>
                            </label>
                        @empty
                            <p class="text-xs text-gray-500">Belum ada tag.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Meta Info --}}
                <div class="admin-card p-5 text-xs text-gray-500 space-y-1">
                    <p><span class="text-gray-400">Dibuat:</span> {{ $post->created_at?->format('d M Y H:i') }}</p>
                    <p><span class="text-gray-400">Diperbarui:</span> {{ $post->updated_at?->format('d M Y H:i') }}</p>
                    <p><span class="text-gray-400">Penulis:</span> {{ $post->user?->name ?? '—' }}</p>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            ['content-id', 'content-en'].forEach(id => {
                const el = document.getElementById(id);
                if (el && window.ClassicEditor) {
                    window.ClassicEditor.create(el).catch(err => console.error(err));
                }
            });
        });
    </script>
@endpush
