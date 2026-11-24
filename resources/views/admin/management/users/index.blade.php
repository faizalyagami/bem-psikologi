@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('breadcrumb', 'Pengguna')

@section('content')

    <x-admin.page-header title="Manajemen Pengguna" description="Kelola akun administrator, pengurus, dan hak aksesnya.">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna
        </a>
    </x-admin.page-header>

    {{-- Alert --}}
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

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="admin-input pl-9">
            </div>
            <select name="role" class="admin-input sm:w-48">
                <option value="">Semua Peran</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            @if (request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i data-lucide="x" class="w-4 h-4"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Peran</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($users as $user)
                        <tr class="hover:bg-dark-hover transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img class="w-9 h-9 rounded-full border border-dark-border"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=173F35&color=D6B66D"
                                        alt="">
                                    <div>
                                        <p class="font-medium text-white">{{ $user->name }}</p>
                                        @if ($user->id === auth()->id())
                                            <span class="text-[10px] text-brand-400">(Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="text-[11px] px-2 py-0.5 rounded-full bg-brand-900/30 text-brand-300 border border-brand-900/50">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-full bg-green-900/30 text-green-400">Aktif</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400 transition"
                                        title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400 transition"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-2 text-gray-700"></i>
                                <p>Belum ada pengguna.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">
                {{ $users->links() }}
            </div>
        @endif
    </div>

@endsection
