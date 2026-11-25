@extends('layouts.frontend')

@section('title', 'Galeri - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Galeri Kegiatan</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Dokumentasi momen berharga BEM Fakultas Psikologi.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($images->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($images as $image)
                        <div class="group relative aspect-square rounded-xl overflow-hidden bg-gray-100"
                            x-data="{ open: false }">
                            <img src="{{ Storage::url($image->path) }}" alt="{{ $image->name }}"
                                class="w-full h-full object-cover cursor-pointer group-hover:scale-105 transition duration-300"
                                @click="open = true">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-end p-3 pointer-events-none">
                                <p class="text-white text-xs truncate">{{ $image->name }}</p>
                            </div>

                            {{-- Lightbox --}}
                            <div x-show="open" x-cloak @click="open = false"
                                class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4">
                                <img src="{{ Storage::url($image->path) }}" class="max-w-full max-h-full">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $images->links() }}</div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada foto di galeri.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
