@extends('layouts.frontend')

@section('title', $translation?->title . ' - ' . setting('site_name'))
@section('meta_description', Str::limit(strip_tags($translation?->description), 150))

@section('content')

    <article class="pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="hover:text-primary">Beranda</a>
                <span>/</span>
                <a href="{{ route('frontend.events', ['locale' => app()->getLocale()]) }}"
                    class="hover:text-primary">Agenda</a>
                <span>/</span>
                <span class="text-primary font-medium truncate">{{ $translation?->title }}</span>
            </nav>

            @if ($event->featured_image)
                <div class="rounded-2xl overflow-hidden mb-8">
                    <img src="{{ Storage::url($event->featured_image) }}" class="w-full h-auto" alt="">
                </div>
            @endif

            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary leading-tight mb-6">
                {{ $translation?->title }}
            </h1>

            <div class="flex flex-wrap gap-4 mb-8 pb-8 border-b border-gray-200">
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-9 h-9 rounded-lg bg-primary text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Mulai</p>
                        <p class="font-medium text-gray-800">{{ $event->start_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                @if ($event->end_date)
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Selesai</p>
                            <p class="font-medium text-gray-800">{{ $event->end_date->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                @endif
                @if ($event->location)
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Lokasi</p>
                            <p class="font-medium text-gray-800">{{ $event->location }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div
                class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-primary prose-a:text-accent">
                {!! $translation?->description !!}
            </div>

        </div>
    </article>

@endsection
