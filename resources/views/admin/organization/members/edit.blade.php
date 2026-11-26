@extends('layouts.admin')
@section('title', 'Edit Anggota')
@section('breadcrumb', 'Organisasi › Edit Anggota')

@section('content')

    <x-admin.page-header title="Edit Anggota" description="Perbarui data {{ $member->name }}.">
        <a href="{{ route('admin.members.index') }}" class="btn-secondary"><i data-lucide="arrow-left" class="w-4 h-4"></i>
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

    <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @csrf @method('PUT')

        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="admin-card p-5 space-y-4">
                <h3 class="font-heading font-semibold text-white">Data Anggota</h3>

                <div>
                    <label class="admin-label">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                        class="admin-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Departemen</label>
                        <select name="department_id" class="admin-input">
                            <option value="">— Pengurus Inti (tanpa departemen) —</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id', $member->department_id) == $d->id)>{{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Jabatan <span class="text-red-400">*</span></label>
                        <select name="position_id" required class="admin-input">
                            @foreach ($positions as $p)
                                <option value="{{ $p->id }}" @selected(old('position_id', $member->position_id) == $p->id)>{{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Instagram</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">@</span>
                            <input type="text" name="instagram" value="{{ old('instagram', $member->instagram) }}"
                                class="admin-input pl-8">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}"
                            class="admin-input">
                    </div>
                </div>

                <div>
                    <label class="admin-label">LinkedIn URL</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin', $member->linkedin) }}"
                        class="admin-input">
                </div>

                <div>
                    <label class="admin-label">Bio Singkat</label>
                    <textarea name="bio" rows="3" class="admin-input">{{ old('bio', $member->bio) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Foto</h3>
                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                    class="w-20 h-20 rounded-full object-cover border-2 border-dark-border mb-3">
                <input type="file" name="photo" accept="image/*"
                    class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
                <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengganti foto.</p>
            </div>

            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Urutan</h3>
                <input type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order) }}"
                    class="admin-input">
            </div>

            <div class="admin-card p-5 text-xs text-gray-500 space-y-1">
                <p><span class="text-gray-400">Ditambahkan:</span> {{ $member->created_at?->format('d M Y H:i') }}</p>
                <p><span class="text-gray-400">Diperbarui:</span> {{ $member->updated_at?->format('d M Y H:i') }}</p>
            </div>

            <div class="admin-card p-5">
                <button type="submit" class="btn-primary w-full justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

@endsection
