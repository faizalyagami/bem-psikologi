@extends('layouts.frontend')
@section('title', $program->title . ' - ' . setting('site_name'))
@section('meta_description', Str::limit(strip_tags($program->description), 150))

@section('content')

    <article class="pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="hover:text-primary">Beranda</a>
                <span>/</span>
                <a href="{{ route('frontend.programs', ['locale' => app()->getLocale()]) }}"
                    class="hover:text-primary">Program Kerja</a>
                <span>/</span>
                <span class="text-primary font-medium truncate">{{ $program->title }}</span>
            </nav>

            @if ($program->featured_image)
                <div class="rounded-2xl overflow-hidden mb-8">
                    <img src="{{ Storage::url($program->featured_image) }}" class="w-full h-auto" alt="">
                </div>
            @endif

            <div class="flex items-center gap-3 mb-4">
                <span
                    class="px-3 py-1 bg-cream text-primary text-sm font-medium rounded-full">{{ $program->department?->name ?? 'Umum' }}</span>
                @php
                    $statusLabel = match ($program->status) {
                        'planned' => ['Direncanakan', 'bg-yellow-100 text-yellow-700'],
                        'ongoing' => ['Berjalan', 'bg-blue-100 text-blue-700'],
                        'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                        'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700'],
                    };
                @endphp
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
            </div>

            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary leading-tight mb-6">
                {{ $program->title }}</h1>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8 pb-8 border-b border-gray-200 text-sm">
                @if ($program->start_date)
                    <div>
                        <p class="text-xs text-gray-500">Mulai</p>
                        <p class="font-medium text-gray-800">{{ $program->start_date->format('d M Y') }}</p>
                    </div>
                @endif
                @if ($program->end_date)
                    <div>
                        <p class="text-xs text-gray-500">Selesai</p>
                        <p class="font-medium text-gray-800">{{ $program->end_date->format('d M Y') }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-gray-500">Penanggung Jawab</p>
                    <p class="font-medium text-gray-800">{{ $program->user?->name ?? '—' }}</p>
                </div>
            </div>

            <div
                class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-primary prose-a:text-accent">
                {!! $program->content ?? '<p class="text-gray-500 italic">Belum ada detail program.</p>' !!}
            </div>

        </div>
    </article>

    @if ($related->count())
        <section class="py-16 bg-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-heading text-2xl font-bold text-primary mb-8">Program Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($related as $rel)
                        <a href="{{ route('frontend.programs.show', ['locale' => app()->getLocale(), 'slug' => $rel->slug]) }}"
                            class="group bg-white rounded-2xl overflow-hidden hover:shadow-lg transition">
                            <div class="aspect-video bg-gray-100">
                                @if ($rel->featured_image)
                                    <img src="{{ Storage::url($rel->featured_image) }}" class="w-full h-full object-cover"
                                        alt="">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3
                                    class="font-heading font-semibold text-primary line-clamp-2 group-hover:text-accent transition">
                                    {{ $rel->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
