@extends('layouts.frontend')

@section('title', 'Agenda Kegiatan - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Agenda Kegiatan</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Jadwal seminar, pelatihan, dan kegiatan BEM Fakultas
                Psikologi.</p>
        </div>
    </section>

    {{-- Agenda Mendatang --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-heading text-2xl font-bold text-primary mb-8 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                Agenda Mendatang
            </h2>

            @if ($upcoming->count())
                <div class="space-y-4">
                    @foreach ($upcoming as $event)
                        @php $tr = $event->translations->firstWhere('locale', app()->getLocale()) ?? $event->translations->firstWhere('locale', 'id'); @endphp
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}"
                            class="group flex flex-col sm:flex-row gap-4 bg-white border border-gray-100 rounded-2xl p-4 hover:shadow-lg transition">
                            <div class="shrink-0 text-center bg-primary text-white rounded-xl px-6 py-3">
                                <p class="text-3xl font-heading font-bold leading-none">
                                    {{ $event->start_date->format('d') }}</p>
                                <p class="text-xs font-semibold uppercase mt-1">{{ $event->start_date->format('M Y') }}</p>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-heading font-bold text-lg text-primary group-hover:text-accent transition">
                                    {{ $tr?->title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mt-1">
                                    {{ Str::limit(strip_tags($tr?->description), 150) }}</p>
                                <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $event->start_date->format('H:i') }} WIB
                                    </span>
                                    @if ($event->location)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $event->location }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-500">Belum ada agenda mendatang.</div>
            @endif
        </div>
    </section>

    {{-- Agenda Lampau --}}
    @if ($past->count())
        <section class="py-16 bg-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-heading text-2xl font-bold text-primary mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                    Agenda Lampau
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($past as $event)
                        @php $tr = $event->translations->firstWhere('locale', app()->getLocale()) ?? $event->translations->firstWhere('locale', 'id'); @endphp
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}"
                            class="bg-white rounded-2xl overflow-hidden hover:shadow-lg transition group">
                            <div class="aspect-video bg-gray-100">
                                @if ($event->featured_image)
                                    <img src="{{ Storage::url($event->featured_image) }}" class="w-full h-full object-cover"
                                        alt="">
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500 mb-2">{{ $event->start_date->format('d M Y') }}</p>
                                <h3
                                    class="font-heading font-semibold text-primary line-clamp-2 group-hover:text-accent transition">
                                    {{ $tr?->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
