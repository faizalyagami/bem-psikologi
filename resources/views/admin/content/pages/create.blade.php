@extends('layouts.admin')
@section('title', 'Tambah Halaman')
@section('breadcrumb', 'Halaman › Tambah')

@section('content')

    <x-admin.page-header title="Tambah Halaman Baru" description="Buat halaman statis.">
        <a href="{{ route('admin.pages.index') }}" class="btn-secondary"><i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali</a>
    </x-admin.page-header>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
        x-data="{ lang: 'id' }">
        @csrf

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
            <div class="lg:col-span-2 space-y-5">
                <div class="admin-card p-5">
                    <div class="flex gap-2 mb-4 border-b border-dark-border">
                        <button type="button" @click="lang='id'"
                            :class="lang === 'id' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm transition">🇮🇩 Indonesia</button>
                        <button type="button" @click="lang='en'"
                            :class="lang === 'en' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm transition">🇬🇧 English <span
                                class="text-[10px] text-gray-500">(opsional)</span></button>
                    </div>

                    {{-- ID --}}
                    <div x-show="lang==='id'" class="space-y-4">
                        <div>
                            <label class="admin-label">Judul (ID) <span class="text-red-400">*</span></label>
                            <input type="text" name="id[title]" value="{{ old('id.title') }}" required
                                class="admin-input" placeholder="Misal: Tentang BEM">
                        </div>
                        <div>
                            <label class="admin-label">Slug (ID) <span
                                    class="text-gray-500 font-normal">(otomatis)</span></label>
                            <input type="text" name="id[slug]" value="{{ old('id.slug') }}"
                                class="admin-input font-mono text-xs">
                        </div>
                        <div>
                            <label class="admin-label">Konten (ID) <span class="text-red-400">*</span></label>
                            <textarea name="id[content]" id="content-id" rows="15" class="admin-input">{{ old('id.content') }}</textarea>
                        </div>
                    </div>

                    {{-- EN --}}
                    <div x-show="lang==='en'" x-cloak class="space-y-4">
                        <div>
                            <label class="admin-label">Title (EN)</label>
                            <input type="text" name="en[title]" value="{{ old('en.title') }}" class="admin-input">
                        </div>
                        <div>
                            <label class="admin-label">Slug (EN)</label>
                            <input type="text" name="en[slug]" value="{{ old('en.slug') }}"
                                class="admin-input font-mono text-xs">
                        </div>
                        <div>
                            <label class="admin-label">Content (EN)</label>
                            <textarea name="en[content]" id="content-en" rows="15" class="admin-input">{{ old('en.content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-4 flex items-center gap-2"><i data-lucide="search"
                            class="w-4 h-4 text-brand-400"></i> SEO Metadata</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="admin-label">Meta Title (ID)</label><input type="text" name="id[meta_title]"
                                value="{{ old('id.meta_title') }}" class="admin-input" maxlength="255"></div>
                        <div><label class="admin-label">Meta Title (EN)</label><input type="text" name="en[meta_title]"
                                value="{{ old('en.meta_title') }}" class="admin-input" maxlength="255"></div>
                        <div><label class="admin-label">Meta Description (ID)</label>
                            <textarea name="id[meta_description]" rows="2" class="admin-input" maxlength="500">{{ old('id.meta_description') }}</textarea>
                        </div>
                        <div><label class="admin-label">Meta Description (EN)</label>
                            <textarea name="en[meta_description]" rows="2" class="admin-input" maxlength="500">{{ old('en.meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-4">Publikasi</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="admin-label">Status</label>
                            <select name="status" class="admin-input" required>
                                <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                <option value="published" @selected(old('status') === 'published')>Published</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center"><i data-lucide="save"
                                class="w-4 h-4"></i> Simpan Halaman</button>
                    </div>
                </div>

                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-3">Gambar Utama <span
                            class="text-gray-500 font-normal text-xs">(opsional)</span></h3>
                    <input type="file" name="featured_image" accept="image/*"
                        class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG, WebP. Maks 2MB.</p>
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
                if (el && window.ClassicEditor) window.ClassicEditor.create(el).catch(e => console.error(
                e));
            });
        });
    </script>
@endpush
