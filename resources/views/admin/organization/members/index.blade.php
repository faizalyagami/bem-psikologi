@extends('layouts.admin')
@section('title', 'Anggota')
@section('breadcrumb', 'Organisasi › Anggota')

@section('content')

    <x-admin.page-header title="Manajemen Anggota" description="Kelola anggota kabinet BEM.">
        <a href="{{ route('admin.members.create') }}" class="btn-primary">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Anggota
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="department" class="admin-input sm:w-64">
                <option value="">Semua Departemen</option>
                @foreach ($departments as $d)
                    <option value="{{ $d->id }}" @selected(request('department') == $d->id)>{{ $d->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->filled('department'))
                <a href="{{ route('admin.members.index') }}" class="btn-secondary"><i data-lucide="x" class="w-4 h-4"></i>
                    Reset</a>
            @endif
        </form>
    </div>

    {{-- Grid Anggota --}}
    @if ($members->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($members as $member)
                <div class="admin-card p-5 text-center">
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                        class="w-20 h-20 rounded-full mx-auto mb-3 object-cover border-2 border-dark-border">
                    <h3 class="font-heading font-semibold text-white">{{ $member->name }}</h3>
                    <p class="text-xs text-brand-400 mt-1">{{ $member->position->name ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $member->department->name ?? 'Pengurus Inti' }}</p>

                    <div class="flex items-center justify-center gap-1 mt-4 pt-3 border-t border-dark-border">
                        <a href="{{ route('admin.members.edit', $member) }}"
                            class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400" title="Edit">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                            onsubmit="return confirm('Hapus anggota {{ $member->name }}?')">
                            @csrf @method('DELETE')
                            <button class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400"
                                title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $members->links() }}</div>
    @else
        <div class="admin-card p-12 text-center">
            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-700"></i>
            <p class="text-gray-500">Belum ada anggota. Klik "Tambah Anggota" untuk memulai.</p>
        </div>
    @endif

@endsection
