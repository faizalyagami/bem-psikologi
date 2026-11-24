@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    <x-admin.page-header title="Ringkasan Dashboard"
        description="Selamat datang kembali, {{ Auth::user()->name }}. Berikut ringkasan aktivitas sistem.">
        <button class="btn-secondary">
            <i data-lucide="download" class="w-4 h-4"></i> Ekspor Laporan
        </button>
        <button class="btn-primary">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Konten
        </button>
    </x-admin.page-header>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $stats = [
                ['label' => 'Total Berita', 'value' => 24, 'icon' => 'newspaper', 'color' => 'brand'],
                ['label' => 'Agenda Kegiatan', 'value' => 12, 'icon' => 'calendar-days', 'color' => 'accent'],
                ['label' => 'Total Anggota', 'value' => 45, 'icon' => 'users', 'color' => 'blue'],
                ['label' => 'Aspirasi Masuk', 'value' => 8, 'icon' => 'message-square', 'color' => 'purple'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="admin-card p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400">{{ $stat['label'] }}</p>
                        <p class="text-3xl font-heading font-bold text-white mt-1">{{ $stat['value'] }}</p>
                        <p class="text-xs text-green-400 mt-1 flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +12% dari bulan lalu
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center
                    @if ($stat['color'] === 'brand') bg-brand-900/30 text-brand-400
                    @elseif($stat['color'] === 'accent') bg-accent/10 text-accent
                    @elseif($stat['color'] === 'blue') bg-blue-900/30 text-blue-400
                    @else bg-purple-900/30 text-purple-400 @endif">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TWO COLUMNS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Aktivitas --}}
        <div class="lg:col-span-2 admin-card">
            <div class="p-5 border-b border-dark-border flex justify-between items-center">
                <h3 class="text-base font-heading font-semibold text-white">Aktivitas Terakhir</h3>
                <a href="{{ route('admin.logs.index') }}" class="text-sm text-brand-400 hover:text-brand-300">Lihat
                    Semua</a>
            </div>
            <div class="p-5">
                <ul class="space-y-4">
                    @php
                        $activities = [
                            [
                                'user' => 'Budi',
                                'action' => 'menambahkan berita baru "Seminar Psikologi"',
                                'time' => '10 menit lalu',
                                'color' => 'brand',
                            ],
                            [
                                'user' => 'Siti',
                                'action' => 'memperbarui agenda "Rapat Kerja"',
                                'time' => '2 jam lalu',
                                'color' => 'accent',
                            ],
                            [
                                'user' => 'Andi',
                                'action' => 'mengunggah 5 file ke Media',
                                'time' => '5 jam lalu',
                                'color' => 'blue',
                            ],
                            [
                                'user' => 'Rina',
                                'action' => 'mempublikasikan halaman "Visi Misi"',
                                'time' => '1 hari lalu',
                                'color' => 'purple',
                            ],
                        ];
                    @endphp
                    @foreach ($activities as $act)
                        <li class="flex gap-4">
                            <div
                                class="w-2 h-2 mt-2 rounded-full shrink-0
                            @if ($act['color'] === 'brand') bg-brand-500
                            @elseif($act['color'] === 'accent') bg-accent
                            @elseif($act['color'] === 'blue') bg-blue-500
                            @else bg-purple-500 @endif">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-300">
                                    <span class="font-semibold text-white">{{ $act['user'] }}</span> {{ $act['action'] }}.
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $act['time'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Aspirasi --}}
        <div class="admin-card">
            <div class="p-5 border-b border-dark-border flex justify-between items-center">
                <h3 class="text-base font-heading font-semibold text-white">Aspirasi Terbaru</h3>
                <a href="#" class="text-sm text-brand-400 hover:text-brand-300">Semua</a>
            </div>
            <div class="p-5 space-y-3">
                <div class="p-3 bg-dark-surface rounded-lg border border-dark-border">
                    <div class="flex justify-between items-start mb-1.5">
                        <span class="text-sm font-semibold text-white">Fasilitas Lab</span>
                        <span class="text-[10px] px-2 py-0.5 bg-yellow-900/30 text-yellow-400 rounded-full">Pending</span>
                    </div>
                    <p class="text-xs text-gray-400 line-clamp-2">Mohon perbaikan AC di ruang laboratorium psikologi lantai
                        2...</p>
                </div>
                <div class="p-3 bg-dark-surface rounded-lg border border-dark-border">
                    <div class="flex justify-between items-start mb-1.5">
                        <span class="text-sm font-semibold text-white">Kegiatan BEM</span>
                        <span class="text-[10px] px-2 py-0.5 bg-green-900/30 text-green-400 rounded-full">Dibalas</span>
                    </div>
                    <p class="text-xs text-gray-400 line-clamp-2">Terima kasih atas program kerja "Psychology Week"...</p>
                </div>
                <div class="p-3 bg-dark-surface rounded-lg border border-dark-border">
                    <div class="flex justify-between items-start mb-1.5">
                        <span class="text-sm font-semibold text-white">Beasiswa</span>
                        <span class="text-[10px] px-2 py-0.5 bg-blue-900/30 text-blue-400 rounded-full">Diproses</span>
                    </div>
                    <p class="text-xs text-gray-400 line-clamp-2">Informasi beasiswa untuk mahasiswa semester akhir...</p>
                </div>
            </div>
        </div>

    </div>

@endsection
