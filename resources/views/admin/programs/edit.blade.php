@extends('layouts.admin')
@section('title', 'Edit Program Kerja')
@section('breadcrumb', 'Program Kerja › Edit')

@section('content')

    <x-admin.page-header title="Edit Program Kerja" description="Perbarui data program.">
        <a href="{{ route('admin.programs.index') }}" class="btn-secondary"><i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali</a>
    </x-admin.page-header>

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @csrf @method('PUT')
        <div class="lg:col-span-2 space-y-5">
            <div class="admin-card p-5 space-y-4">
                <div>
                    <label class="admin-label">Judul Program <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $program->title) }}" required
                        class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $program->slug) }}"
                        class="admin-input font-mono text-xs">
                </div>
                <div>
                    <label class="admin-label">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="admin-input" maxlength="500">{{ old('description', $program->description) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Detail Program</label>
                    <textarea name="content" id="content" rows="10" class="admin-input">{{ old('content', $program->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="admin-card p-5 space-y-3">
                <h3 class="font-heading font-semibold text-white mb-2">Publikasi</h3>
                <div>
                    <label class="admin-label">Departemen</label>
                    <select name="department_id" class="admin-input">
                        <option value="">— Umum —</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}" @selected(old('department_id', $program->department_id) == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Status</label>
                    <select name="status" class="admin-input">
                        <option value="planned" @selected(old('status', $program->status) === 'planned')>Direncanakan</option>
                        <option value="ongoing" @selected(old('status', $program->status) === 'ongoing')>Berjalan</option>
                        <option value="completed" @selected(old('status', $program->status) === 'completed')>Selesai</option>
                        <option value="cancelled" @selected(old('status', $program->status) === 'cancelled')>Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                        value="{{ old('start_date', $program->start_date?->format('Y-m-d')) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Tanggal Selesai</label>
                    <input type="date" name="end_date"
                        value="{{ old('end_date', $program->end_date?->format('Y-m-d')) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order) }}"
                        class="admin-input">
                </div>
            </div>

            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Gambar Utama</h3>
                @if ($program->featured_image)
                    <img src="{{ Storage::url($program->featured_image) }}" class="w-full h-32 object-cover rounded mb-2">
                @endif
                <input type="file" name="featured_image" accept="image/*"
                    class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
            </div>

            <div class="admin-card p-5">
                <button type="submit" class="btn-primary w-full justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('content');
            if (el && window.ClassicEditor) window.ClassicEditor.create(el).catch(e => console.error(e));
        });
    </script>
@endpush
