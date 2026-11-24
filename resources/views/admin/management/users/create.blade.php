@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('breadcrumb', 'Pengguna › Tambah')

@section('content')

    <x-admin.page-header title="Tambah Pengguna" description="Buat akun administrator atau pengurus baru.">
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
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

    <form action="{{ route('admin.users.store') }}" method="POST" class="admin-card p-6 max-w-2xl space-y-5">
        @csrf

        <div>
            <label class="admin-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="admin-input"
                placeholder="Misal: Budi Santoso">
        </div>

        <div>
            <label class="admin-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="admin-input"
                placeholder="nama@bem-psikologi.test">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="admin-label">Password</label>
                <input type="password" name="password" required class="admin-input" placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label class="admin-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="admin-input"
                    placeholder="Ulangi password">
            </div>
        </div>

        <div>
            <label class="admin-label">Peran</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach ($roles as $role)
                    <label
                        class="flex items-center gap-3 p-3 bg-dark-surface border border-dark-border rounded-lg cursor-pointer hover:border-brand-600 transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', [])))
                            class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600">
                        <div>
                            <p class="text-sm font-medium text-white">{{ $role->name }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-dark-border">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Pengguna
            </button>
        </div>
    </form>

@endsection
