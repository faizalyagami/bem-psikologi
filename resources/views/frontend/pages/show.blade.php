@extends('layouts.frontend')

@section('title', ($translation?->meta_title ?? $translation?->title) . ' - ' . setting('site_name'))
@section('meta_description', $translation?->meta_description ?? Str::limit(strip_tags($translation?->content), 150))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">{{ $translation?->title }}</h1>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($page->featured_image)
                <img src="{{ Storage::url($page->featured_image) }}" class="w-full h-auto rounded-2xl mb-8" alt="">
            @endif
            <div
                class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-primary prose-a:text-accent">
                {!! $translation?->content !!}
            </div>
        </div>
    </section>

@endsection
