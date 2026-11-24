@extends('layouts.admin')

@section('title', 'Peran & Izin Akses')
@section('breadcrumb', 'Peran & Izin Akses')

@section('content')

    <x-admin.page-header title="Peran & Izin Akses" description="Kelola peran dan tentukan hak akses setiap peran.">
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Form Tambah Role --}}
    <div class="admin-card p-5 mb-6" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center gap-2 text-white font-heading font-semibold">
            <i data-lucide="plus-circle" class="w-5 h-5 text-brand-400"></i> Tambah Peran Baru
            <i data-lucide="chevron-down" class="w-4 h-4 ml-auto" :class="open ? 'rotate-180' : ''"></i>
        </button>

        <form x-show="open" x-cloak action="{{ route('admin.roles.store') }}" method="POST"
            class="mt-4 pt-4 border-t border-dark-border space-y-4">
            @csrf
            <div>
                <label class="admin-label">Nama Peran</label>
                <input type="text" name="name" required class="admin-input" placeholder="Misal: Bendahara">
            </div>
            <div>
                <label class="admin-label">Izin Akses</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach ($permissions->flatten() as $perm)
                        <label
                            class="flex items-center gap-2 p-2 bg-dark-surface border border-dark-border rounded-lg cursor-pointer hover:border-brand-600 text-xs">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600">
                            <span class="text-gray-300">{{ $perm->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Peran
            </button>
        </form>
    </div>

    {{-- Daftar Role --}}
    <div class="space-y-4">
        @foreach ($roles as $role)
            <div class="admin-card p-5" x-data="{ editing: false }">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-brand-900/30 flex items-center justify-center text-brand-400">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold text-white">{{ $role->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $role->users_count }} pengguna •
                                {{ $role->permissions->count() }} izin</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="editing = !editing" class="btn-secondary">
                            <i data-lucide="pencil" class="w-4 h-4"></i> Edit
                        </button>
                        @if ($role->name !== 'Super Admin')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                onsubmit="return confirm('Hapus peran {{ $role->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- View Mode: Daftar Izin --}}
                <div x-show="!editing" class="mt-4 flex flex-wrap gap-1.5">
                    @forelse($role->permissions as $perm)
                        <span
                            class="text-[11px] px-2 py-1 rounded bg-dark-surface border border-dark-border text-gray-400">{{ $perm->name }}</span>
                    @empty
                        <span class="text-xs text-gray-600 italic">Belum ada izin yang diberikan.</span>
                    @endforelse
                </div>

                {{-- Edit Mode: Permission Matrix --}}
                <form x-show="editing" x-cloak action="{{ route('admin.roles.update', $role) }}" method="POST"
                    class="mt-4 pt-4 border-t border-dark-border space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="admin-label">Nama Peran</label>
                        <input type="text" name="name" value="{{ $role->name }}" required class="admin-input"
                            {{ $role->name === 'Super Admin' ? 'readonly' : '' }}>
                    </div>
                    <div>
                        <label class="admin-label">Izin Akses</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach ($permissions->flatten() as $perm)
                                <label
                                    class="flex items-center gap-2 p-2 bg-dark-surface border border-dark-border rounded-lg cursor-pointer hover:border-brand-600 text-xs">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        @checked($role->hasPermissionTo($perm->name))
                                        class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600">
                                    <span class="text-gray-300">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                        </button>
                        <button type="button" @click="editing = false" class="btn-secondary">Batal</button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

@endsection
