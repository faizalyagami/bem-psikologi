@extends('layouts.admin')
@section('title', 'Detail Aspirasi')
@section('breadcrumb', 'Aspirasi › Detail')

@section('content')

    <x-admin.page-header title="Detail Aspirasi" description="Baca pesan dan balas aspirasi mahasiswa.">
        <a href="{{ route('admin.aspirations.index') }}" class="btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Kolom Kiri: Pesan & Balasan --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Pesan Asli --}}
            <div class="admin-card p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="font-heading text-xl font-bold text-white">{{ $aspiration->subject }}</h2>
                        <p class="text-xs text-gray-500 mt-1">
                            Dari {{ $aspiration->is_anonymous ? 'Anonim' : $aspiration->name }}
                            @if (!$aspiration->is_anonymous)
                                &lt;{{ $aspiration->email }}&gt;
                            @endif
                            · {{ $aspiration->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    @php
                        $statusColor = match ($aspiration->status) {
                            'pending' => 'bg-yellow-900/30 text-yellow-400',
                            'processing' => 'bg-blue-900/30 text-blue-400',
                            'replied' => 'bg-green-900/30 text-green-400',
                            'closed' => 'bg-gray-800 text-gray-400',
                        };
                    @endphp
                    <span
                        class="text-[11px] px-2 py-0.5 rounded-full {{ $statusColor }}">{{ ucfirst($aspiration->status) }}</span>
                </div>
                <div class="prose prose-invert prose-sm max-w-none">
                    {!! nl2br(e($aspiration->message)) !!}
                </div>
            </div>

            {{-- Riwayat Balasan --}}
            <div class="admin-card p-6">
                <h3 class="font-heading font-semibold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="message-circle" class="w-4 h-4 text-brand-400"></i>
                    Riwayat Balasan ({{ $aspiration->replies->count() }})
                </h3>

                @forelse($aspiration->replies as $reply)
                    <div class="p-4 bg-dark-surface rounded-lg border border-dark-border mb-3">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=173F35&color=D6B66D"
                                class="w-7 h-7 rounded-full">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $reply->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-300">{{ $reply->message }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-4">Belum ada balasan.</p>
                @endforelse

                {{-- Form Balas --}}
                @can('manage-aspirations')
                    <form action="{{ route('admin.aspirations.reply', $aspiration) }}" method="POST"
                        class="mt-4 pt-4 border-t border-dark-border space-y-3">
                        @csrf
                        <div>
                            <label class="admin-label">Balasan Anda</label>
                            <textarea name="message" rows="4" required class="admin-input" placeholder="Tulis balasan..."></textarea>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <label class="admin-label">Ubah Status</label>
                                <select name="status" class="admin-input">
                                    <option value="pending" @selected($aspiration->status === 'pending')>Pending</option>
                                    <option value="processing" @selected($aspiration->status === 'processing')>Diproses</option>
                                    <option value="replied" @selected($aspiration->status === 'replied')>Dibalas</option>
                                    <option value="closed" @selected($aspiration->status === 'closed')>Ditutup</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary mt-6">
                                <i data-lucide="send" class="w-4 h-4"></i> Kirim Balasan
                            </button>
                        </div>
                    </form>
                @endcan
            </div>
        </div>

        {{-- Kolom Kanan: Aksi Cepat --}}
        <div class="space-y-5">
            <div class="admin-card p-5">
                <h3 class="font-heading font-semibold text-white mb-3">Aksi Cepat</h3>
                <form action="{{ route('admin.aspirations.status', $aspiration) }}" method="POST" class="space-y-2">
                    @csrf @method('PUT')
                    <label class="admin-label">Ubah Status Tanpa Balas</label>
                    <select name="status" class="admin-input">
                        <option value="pending" @selected($aspiration->status === 'pending')>Pending</option>
                        <option value="processing" @selected($aspiration->status === 'processing')>Diproses</option>
                        <option value="replied" @selected($aspiration->status === 'replied')>Dibalas</option>
                        <option value="closed" @selected($aspiration->status === 'closed')>Ditutup</option>
                    </select>
                    <button class="btn-secondary w-full justify-center" type="submit">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Update Status
                    </button>
                </form>
            </div>

            <div class="admin-card p-5 text-sm text-gray-400 space-y-1">
                <p><span class="text-gray-500">Dibuat:</span> {{ $aspiration->created_at->format('d M Y, H:i') }}</p>
                <p><span class="text-gray-500">Anonim:</span> {{ $aspiration->is_anonymous ? 'Ya' : 'Tidak' }}</p>
                <p><span class="text-gray-500">Total balasan:</span> {{ $aspiration->replies->count() }}</p>
            </div>
        </div>
    </div>

@endsection
