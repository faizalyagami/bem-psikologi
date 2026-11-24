@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('breadcrumb', 'Pengguna › Edit')

@section('content')

    <x-admin.page-header title="Edit Pengguna" description="Perbarui data {{ $user->name }}.">
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

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="admin-card p-6 max-w-2xl space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="admin-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="admin-input">
        </div>

        <div>
            <label class="admin-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="admin-input">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="admin-label">Password Baru <span class="text-gray-500 font-normal">(opsional)</span></label>
                <input type="password" name="password" class="admin-input" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div>
                <label class="admin-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="admin-input">
            </div>
        </div>

        <div>
            <label class="admin-label">Peran</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach ($roles as $role)
                    <label
                        class="flex items-center gap-3 p-3 bg-dark-surface border border-dark-border rounded-lg cursor-pointer hover:border-brand-600 transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())))
                            class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600">
                        <p class="text-sm font-medium text-white">{{ $role->name }}</p>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-dark-border">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>
    </form>

@endsection
