@extends('layouts.admin')
@section('title', 'Program Kerja')
@section('breadcrumb', 'Program Kerja')

@section('content')

    <x-admin.page-header title="Program Kerja" description="Kelola program kerja BEM per departemen.">
        <a href="{{ route('admin.programs.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Program
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
            <select name="status" class="admin-input sm:w-48">
                <option value="">Semua Status</option>
                <option value="planned" @selected(request('status') === 'planned')>Direncanakan</option>
                <option value="ongoing" @selected(request('status') === 'ongoing')>Berjalan</option>
                <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Dibatalkan</option>
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->hasAny(['department', 'status']))
                <a href="{{ route('admin.programs.index') }}" class="btn-secondary"><i data-lucide="x" class="w-4 h-4"></i>
                    Reset</a>
            @endif
        </form>
    </div>

    {{-- Grid Program --}}
    @if ($programs->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($programs as $program)
                @php
                    $statusBadge = match ($program->status) {
                        'planned' => 'bg-yellow-900/30 text-yellow-400',
                        'ongoing' => 'bg-blue-900/30 text-blue-400',
                        'completed' => 'bg-green-900/30 text-green-400',
                        'cancelled' => 'bg-red-900/30 text-red-400',
                    };
                @endphp
                <div class="admin-card overflow-hidden">
                    <div class="aspect-video bg-dark-surface relative">
                        @if ($program->featured_image)
                            <img src="{{ Storage::url($program->featured_image) }}" class="w-full h-full object-cover"
                                alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-700">
                                <i data-lucide="image" class="w-12 h-12"></i>
                            </div>
                        @endif
                        <span
                            class="absolute top-2 right-2 text-[10px] px-2 py-0.5 rounded-full {{ $statusBadge }}">{{ ucfirst($program->status) }}</span>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-brand-400 font-medium">{{ $program->department?->name ?? 'Umum' }}</p>
                        <h3 class="font-heading font-semibold text-white mt-1 line-clamp-2">{{ $program->title }}</h3>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ Str::limit($program->description, 100) }}</p>
                        <div class="flex items-center justify-end gap-1 mt-3 pt-3 border-t border-dark-border">
                            <a href="{{ route('admin.programs.edit', $program) }}"
                                class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.programs.destroy', $program) }}" method="POST"
                                onsubmit="return confirm('Hapus program ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $programs->links() }}</div>
    @else
        <div class="admin-card p-12 text-center">
            <i data-lucide="target" class="w-12 h-12 mx-auto mb-3 text-gray-700"></i>
            <p class="text-gray-500">Belum ada program kerja.</p>
        </div>
    @endif

@endsection
