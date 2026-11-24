@extends('layouts.admin')
@section('title', 'Edit Agenda')
@section('breadcrumb', 'Agenda › Edit')

@section('content')

    <x-admin.page-header title="Edit Agenda" description="Perbarui jadwal kegiatan.">
        <a href="{{ route('admin.events.index') }}" class="btn-secondary"><i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali</a>
    </x-admin.page-header>

    @php
        $trId = $event->translations->firstWhere('locale', 'id');
        $trEn = $event->translations->firstWhere('locale', 'en');
        $startValue = old(
            'start_date',
            $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') : '',
        );
        $endValue = old(
            'end_date',
            $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '',
        );
    @endphp

    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
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
            <div class="lg:col-span-2 space-y-5">
                <div class="admin-card p-5">
                    <div class="flex gap-2 mb-4 border-b border-dark-border">
                        <button type="button" @click="lang='id'"
                            :class="lang === 'id' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm">🇮🇩 Indonesia</button>
                        <button type="button" @click="lang='en'"
                            :class="lang === 'en' ? 'border-brand-500 text-brand-400' : 'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-medium text-sm">🇬🇧 English</button>
                    </div>

                    <div x-show="lang==='id'" class="space-y-4">
                        <div><label class="admin-label">Judul Agenda (ID) <span class="text-red-400">*</span></label><input
                                type="text" name="id[title]" value="{{ old('id.title', $trId?->title) }}" required
                                class="admin-input"></div>
                        <div><label class="admin-label">Slug (ID)</label><input type="text" name="id[slug]"
                                value="{{ old('id.slug', $trId?->slug) }}" class="admin-input font-mono text-xs"></div>
                        <div><label class="admin-label">Deskripsi (ID)</label>
                            <textarea name="id[description]" id="content-id" rows="10" class="admin-input">{{ old('id.description', $trId?->description) }}</textarea>
                        </div>
                    </div>

                    <div x-show="lang==='en'" x-cloak class="space-y-4">
                        <div><label class="admin-label">Title (EN)</label><input type="text" name="en[title]"
                                value="{{ old('en.title', $trEn?->title) }}" class="admin-input"></div>
                        <div><label class="admin-label">Slug (EN)</label><input type="text" name="en[slug]"
                                value="{{ old('en.slug', $trEn?->slug) }}" class="admin-input font-mono text-xs"></div>
                        <div><label class="admin-label">Description (EN)</label>
                            <textarea name="en[description]" id="content-en" rows="10" class="admin-input">{{ old('en.description', $trEn?->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-4">Jadwal & Publikasi</h3>
                    <div class="space-y-3">
                        <div><label class="admin-label">Mulai <span class="text-red-400">*</span></label><input
                                type="datetime-local" name="start_date" value="{{ $startValue }}" required
                                class="admin-input"></div>
                        <div><label class="admin-label">Selesai</label><input type="datetime-local" name="end_date"
                                value="{{ $endValue }}" class="admin-input"></div>
                        <div><label class="admin-label">Lokasi</label><input type="text" name="location"
                                value="{{ old('location', $event->location) }}" class="admin-input"></div>
                        <div>
                            <label class="admin-label">Status</label>
                            <select name="status" class="admin-input" required>
                                <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $event->status) === 'published')>Published</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center"><i data-lucide="save"
                                class="w-4 h-4"></i> Simpan Perubahan</button>
                    </div>
                </div>

                <div class="admin-card p-5">
                    <h3 class="font-heading font-semibold text-white mb-3">Gambar Utama</h3>
                    @if ($event->featured_image)
                        <img src="{{ Storage::url($event->featured_image) }}"
                            class="w-full h-32 object-cover rounded border border-dark-border mb-3">
                    @endif
                    <input type="file" name="featured_image" accept="image/*"
                        class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
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
