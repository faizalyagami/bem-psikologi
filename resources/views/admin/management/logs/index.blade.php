@extends('layouts.admin')
@section('title', 'Log Audit')
@section('breadcrumb', 'Log Audit')

@section('content')

    <x-admin.page-header title="Log Audit" description="Riwayat aktivitas sistem dan pengguna."></x-admin.page-header>

    {{-- Filter --}}
    <div class="admin-card p-4 mb-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Cari aksi..."
                class="admin-input flex-1">
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Cari pengguna..."
                class="admin-input flex-1">
            <input type="date" name="date" value="{{ request('date') }}" class="admin-input sm:w-48">
            <button type="submit" class="btn-secondary"><i data-lucide="filter" class="w-4 h-4"></i> Filter</button>
            @if (request()->hasAny(['action', 'user', 'date']))
                <a href="{{ route('admin.logs.index') }}" class="btn-secondary"><i data-lucide="x" class="w-4 h-4"></i>
                    Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="admin-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-dark-surface border-b border-dark-border">
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase">
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Pengguna</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
                @forelse($logs as $log)
                    <tr class="hover:bg-dark-hover">
                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d M Y, H:i:s') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($log->user)
                                <div class="flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&background=173F35&color=D6B66D"
                                        class="w-7 h-7 rounded-full">
                                    <span class="text-gray-200 text-xs">{{ $log->user->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-500 text-xs italic">System</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="text-[11px] px-2 py-0.5 rounded-full bg-brand-900/30 text-brand-300 font-mono">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400">
                            @if ($log->properties)
                                {{ Str::limit(json_encode($log->properties, JSON_UNESCAPED_UNICODE), 100) }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-500">Belum ada log aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($logs->hasPages())
            <div class="px-4 py-3 border-t border-dark-border">{{ $logs->links() }}</div>
        @endif
    </div>

@endsection
