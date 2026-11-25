@extends('layouts.frontend')

@section('title', ($translation?->meta_title ?? 'Tentang BEM') . ' - ' . setting('site_name'))
@section('meta_description', $translation?->meta_description ?? 'Tentang BEM Fakultas Psikologi')

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">
                {{ $translation?->title ?? 'Tentang BEM' }}</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Mengenal lebih dekat Badan Eksekutif Mahasiswa Fakultas
                Psikologi.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($page?->featured_image)
                <img src="{{ Storage::url($page->featured_image) }}" class="w-full h-auto rounded-2xl mb-8" alt="">
            @endif

            @if ($translation)
                <div
                    class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-primary prose-a:text-accent">
                    {!! $translation->content !!}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">Halaman "Tentang BEM" belum dibuat. Silakan buat di admin panel.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
