@extends('layouts.admin')
@section('title', 'Agenda Kegiatan')
@section('breadcrumb', 'Agenda')

@section('content')

    <x-admin.page-header title="Agenda Kegiatan" description="Kelola jadwal seminar, pelatihan, dan kegiatan BEM.">
        <a href="{{ route('admin.events.create') }}" class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Agenda
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul agenda..."
                    class="admin-input pl-9">
            </div>
            <select name="status" class="admin-input sm:w-40">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
            </select>
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
        </form>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-dark-surface border-b border-dark-border">
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-border">
                    @forelse($events as $event)
                        @php $tr = $event->translation('id'); @endphp
                        <tr class="hover:bg-dark-hover">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($event->featured_image)
                                        <img src="{{ Storage::url($event->featured_image) }}"
                                            class="w-10 h-10 rounded object-cover border border-dark-border">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded bg-dark-surface border border-dark-border flex items-center justify-center">
                                            <i data-lucide="calendar" class="w-4 h-4 text-gray-600"></i>
                                        </div>
                                    @endif
                                    <p class="font-medium text-white truncate">{{ $tr?->title ?? '—' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-300 text-xs">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}
                                @if ($event->end_date)
                                    <br><span class="text-gray-500">s/d
                                        {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $event->location ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php $badge = $event->status === 'published' ? 'bg-green-900/30 text-green-400' : 'bg-yellow-900/30 text-yellow-400'; @endphp
                                <span
                                    class="text-[11px] px-2 py-0.5 rounded-full {{ $badge }}">{{ ucfirst($event->status) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                        class="p-2 rounded-lg hover:bg-dark-hover text-gray-400 hover:text-brand-400"><i
                                            data-lucide="pencil" class="w-4 h-4"></i></a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                        onsubmit="return confirm('Hapus agenda ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-lg hover:bg-red-900/20 text-gray-400 hover:text-red-400"><i
                                                data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                                <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-2 text-gray-700"></i>
                                <p>Belum ada agenda kegiatan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($events->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $events->links() }}</div>
        @endif
    </div>

@endsection
