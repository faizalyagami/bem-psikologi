@extends('layouts.admin')
@section('title', 'Tambah Anggota')
@section('breadcrumb', 'Organisasi › Tambah Anggota')

@section('content')

    <x-admin.page-header title="Tambah Anggota" description="Tambahkan anggota baru ke struktur organisasi.">
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

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @csrf

        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="admin-card p-5 space-y-4">
                <h3 class="font-heading font-semibold text-white">Data Anggota</h3>

                <div>
                    <label class="admin-label">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="admin-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Departemen <span
                                class="text-gray-500 font-normal">(opsional)</span></label>
                        <select name="department_id" class="admin-input">
                            <option value="">— Pengurus Inti (tanpa departemen) —</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}" @selected(old('department_id') == $d->id)>{{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Jabatan <span class="text-red-400">*</span></label>
                        <select name="position_id" required class="admin-input">
                            <option value="">— Pilih Jabatan —</option>
                            @foreach ($positions as $p)
                                <option value="{{ $p->id }}" @selected(old('position_id') == $p->id)>{{ $p->name }}
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
                            <input type="text" name="instagram" value="{{ old('instagram') }}" class="admin-input pl-8"
                                placeholder="username">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="admin-input"
                            placeholder="nama@unisba.ac.id">
                    </div>
                </div>

                <div>
                    <label class="admin-label">LinkedIn URL</label>
                    <input type="text" name="linkedin" value="{{ old('linkedin') }}" class="admin-input"
                        placeholder="https://linkedin.com/in/username">
                </div>

                <div>
                    <label class="admin-label">Bio Singkat</label>
                    <textarea name="bio" rows="3" class="admin-input" placeholder="Deskripsi singkat...">{{ old('bio') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Foto</h3>
                <input type="file" name="photo" accept="image/*"
                    class="admin-input text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:bg-brand-700 file:text-white hover:file:bg-brand-600">
                <p class="text-xs text-gray-500 mt-2">JPG, PNG. Maks 2MB. Akan otomatis pakai avatar jika kosong.</p>
            </div>

            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Urutan</h3>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="admin-input">
                <p class="text-xs text-gray-500 mt-2">Angka kecil tampil lebih dulu.</p>
            </div>

            <div class="admin-card p-5">
                <button type="submit" class="btn-primary w-full justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Anggota
                </button>
            </div>
        </div>
    </form>

@endsection
