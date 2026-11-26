@extends('layouts.admin')
@section('title', 'Aspirasi Mahasiswa')
@section('breadcrumb', 'Aspirasi')

@section('content')

    <x-admin.page-header title="Aspirasi Mahasiswa"
        description="Kelola aspirasi, saran, dan pertanyaan dari mahasiswa."></x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @php
            $badges = [
                'pending' => ['label' => 'Pending', 'color' => 'yellow'],
                'processing' => ['label' => 'Diproses', 'color' => 'blue'],
                'replied' => ['label' => 'Dibalas', 'color' => 'green'],
                'closed' => ['label' => 'Ditutup', 'color' => 'gray'],
            ];
        @endphp
        @foreach ($badges as $key => $b)
            <div class="admin-card p-4">
                <p class="text-xs text-gray-500 uppercase">{{ $b['label'] }}</p>
                <p class="text-2xl font-heading font-bold text-white mt-1">{{ $stats[$key] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari subjek, nama, atau email..." class="admin-input pl-9">
            </div>
            <select name="status" class="admin-input sm:w-48">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="processing" @selected(request('status') === 'processing')>Diproses</option>
                <option value="replied" @selected(request('status') === 'replied')>Dibalas</option>
                <option value="closed" @selected(request('status') === 'closed')>Ditutup</option>
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.aspirations.index') }}" class="btn-secondary"><i data-lucide="x"
                        class="w-4 h-4"></i> Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase">
                        <th class="px-4 py-3">Pengirim</th>
                        <th class="px-4 py-3">Subjek</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Balasan</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($aspirations as $asp)
                        @php
                            $statusColor = match ($asp->status) {
                                'pending' => 'bg-yellow-900/30 text-yellow-400',
                                'processing' => 'bg-blue-900/30 text-blue-400',
                                'replied' => 'bg-green-900/30 text-green-400',
                                'closed' => 'bg-gray-800 text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-dark-hover">
                            <td class="px-4 py-3">
                                <p class="font-medium text-white">{{ $asp->is_anonymous ? 'Anonim' : $asp->name }}</p>
                                <p class="text-xs text-gray-500">{{ $asp->is_anonymous ? '—' : $asp->email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.aspirations.show', $asp) }}"
                                    class="text-gray-200 hover:text-brand-400 font-medium">
                                    {{ Str::limit($asp->subject, 50) }}
                                </a>
                                <p class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($asp->message, 80) }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-full {{ $statusColor }}">{{ ucfirst($asp->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $asp->replies_count }} balasan</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $asp->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.aspirations.show', $asp) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400"
                                        title="Lihat & Balas">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.aspirations.destroy', $asp) }}" method="POST"
                                        onsubmit="return confirm('Hapus aspirasi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">Belum ada aspirasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($aspirations->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $aspirations->links() }}</div>
        @endif
    </div>

@endsection
