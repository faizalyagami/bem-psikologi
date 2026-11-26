@extends('layouts.frontend')

@section('title', setting('seo_meta_title', setting('site_name')))
@section('meta_description', setting('seo_meta_description'))

@section('content')

    {{-- HERO --}}
    <section class="relative min-h-[90vh] flex items-center overflow-hidden pt-20"
        style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-72 h-72 rounded-full bg-accent blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 rounded-full bg-accent blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <span
                        class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur text-accent text-sm font-medium mb-6">
                        ✦ {{ setting('site_tagline', 'Fakultas Psikologi') }}
                    </span>
                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        {!! nl2br(e(site_content('hero_title', 'Bersama Berkarya, Bersama Berdampak'))) !!}
                    </h1>
                    <p class="text-lg text-white/80 leading-relaxed mb-8 max-w-lg">
                        {{ site_content('hero_subtitle', setting('site_description')) }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-primary font-semibold rounded-full hover:bg-opacity-90 transition">
                            Kenali Kami
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur text-white font-semibold rounded-full hover:bg-white/20 transition">
                            Berita Terbaru
                        </a>
                    </div>
                </div>

                {{-- Visual --}}
                <div class="hidden lg:block relative">
                    <div class="relative w-full aspect-square">
                        <div class="absolute inset-0 rounded-3xl overflow-hidden border-4 border-white/10 rotate-3">
                            @if ($latestPosts->first()?->featured_image)
                                <img src="{{ Storage::url($latestPosts->first()->featured_image) }}"
                                    class="w-full h-full object-cover" alt="">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-accent/30 to-white/5 flex items-center justify-center">
                                    <span class="text-9xl text-white/20">Ψ</span>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-4 shadow-2xl">
                            <p class="text-3xl font-heading font-bold text-primary">100+</p>
                            <p class="text-xs text-gray-500">Mahasiswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SAMBUTAN KETUA --}}
    <section class="py-20 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative">
                    <div class="aspect-square rounded-3xl overflow-hidden bg-gradient-to-br from-primary to-secondary">
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-[15rem] text-white/10 font-heading font-extrabold">"</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-accent rounded-2xl p-6 shadow-xl">
                        <p class="font-heading font-bold text-primary text-lg">Ketua BEM</p>
                        <p class="text-sm text-primary/70">2025/2026</p>
                    </div>
                </div>

                <div>
                    <span class="text-accent font-semibold text-sm uppercase tracking-wider">Sambutan Ketua</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-primary mt-3 mb-6">
                        {{ site_content('welcome_title', 'Sinergi untuk Psikologi yang Berdampak') }}
                    </h2>
                    <blockquote class="text-lg text-gray-700 leading-relaxed border-l-4 border-accent pl-6 italic">
                        "{{ site_content('welcome_message') }}"
                    </blockquote>
                    <p class="mt-6 text-gray-600">— {{ site_content('welcome_name', 'Ketua BEM') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- BERITA TERBARU --}}
    @if ($latestPosts->count())
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <span class="text-accent font-semibold text-sm uppercase tracking-wider">Berita</span>
                        <h2 class="font-heading text-3xl sm:text-4xl font-bold text-primary mt-3">Informasi Terbaru</h2>
                    </div>
                    <a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}"
                        class="hidden sm:inline-flex items-center gap-2 text-primary font-semibold hover:text-accent transition">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($latestPosts as $post)
                        @php
                            $tr =
                                $post->translations->firstWhere('locale', app()->getLocale()) ??
                                $post->translations->firstWhere('locale', 'id');
                        @endphp
                        <article
                            class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition">
                            <a href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}"
                                class="block">
                                <div class="aspect-video overflow-hidden bg-gray-100">
                                    @if ($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            alt="{{ $tr?->title }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                    @if ($post->category)
                                        <span
                                            class="px-2 py-0.5 bg-cream text-primary rounded-full font-medium">{{ $post->category->name }}</span>
                                    @endif
                                    <span>{{ $post->published_at?->format('d M Y') }}</span>
                                </div>
                                <h3
                                    class="font-heading font-bold text-lg text-primary mb-2 line-clamp-2 group-hover:text-accent transition">
                                    <a
                                        href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}">
                                        {{ $tr?->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit(strip_tags($tr?->excerpt ?? $tr?->content), 120) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- AGENDA --}}
    @if ($upcomingEvents->count())
        <section class="py-20 bg-primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="text-accent font-semibold text-sm uppercase tracking-wider">Agenda</span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-bold text-white mt-3">Kegiatan Mendatang</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($upcomingEvents as $event)
                        @php
                            $tr =
                                $event->translations->firstWhere('locale', app()->getLocale()) ??
                                $event->translations->firstWhere('locale', 'id');
                        @endphp
                        <div
                            class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="text-center bg-accent text-primary rounded-xl px-4 py-2">
                                    <p class="text-2xl font-heading font-bold leading-none">
                                        {{ $event->start_date->format('d') }}</p>
                                    <p class="text-xs font-semibold uppercase">{{ $event->start_date->format('M') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-accent font-semibold uppercase tracking-wider">Agenda</p>
                                    <p class="text-sm text-white/60">{{ $event->start_date->format('H:i') }} WIB</p>
                                </div>
                            </div>
                            <h3 class="font-heading font-bold text-white text-lg mb-2 line-clamp-2">{{ $tr?->title }}
                            </h3>
                            <p class="text-sm text-white/60 line-clamp-2 mb-4">
                                {{ Str::limit(strip_tags($tr?->description), 100) }}</p>
                            @if ($event->location)
                                <p class="text-xs text-white/50 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $event->location }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA KONTAK --}}
    <section class="py-20 bg-cream">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading text-3xl sm:text-4xl font-bold text-primary mb-4">
                {{ site_content('cta_title', 'Punya Aspirasi?') }}
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                {{ site_content('cta_description') }}
            </p>
            <a href="{{ route('frontend.contact', ['locale' => app()->getLocale()]) }}" ...>
                {{ site_content('cta_button_text', 'Kirim Aspirasi') }}
            </a>
            {{-- <h2 class="font-heading text-3xl sm:text-4xl font-bold text-primary mb-4">Punya Aspirasi?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Suarakan pendapat, saran, atau keluhan Anda untuk kemajuan Fakultas Psikologi. Kami siap mendengarkan.
            </p>
            <a href="{{ route('frontend.contact', ['locale' => app()->getLocale()]) }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white font-semibold rounded-full hover:bg-opacity-90 transition">
                Kirim Aspirasi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a> --}}
        </div>
    </section>

@endsection
