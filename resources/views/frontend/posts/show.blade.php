@extends('layouts.frontend')

@section('title', ($translation?->meta_title ?? $translation?->title) . ' - ' . setting('site_name'))
@section('meta_description', $translation?->meta_description ?? Str::limit(strip_tags($translation?->content), 150))

@section('content')

    <article class="pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="hover:text-primary">Beranda</a>
                <span>/</span>
                <a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}"
                    class="hover:text-primary">Berita</a>
                <span>/</span>
                <span class="text-primary font-medium truncate">{{ $translation?->title }}</span>
            </nav>

            {{-- Category & Date --}}
            <div class="flex items-center gap-3 mb-4">
                @if ($post->category)
                    <span
                        class="px-3 py-1 bg-cream text-primary text-sm font-medium rounded-full">{{ $post->category->name }}</span>
                @endif
                <span class="text-sm text-gray-500">{{ $post->published_at?->format('d F Y') }}</span>
            </div>

            {{-- Title --}}
            <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-primary leading-tight mb-6">
                {{ $translation?->title }}
            </h1>

            {{-- Author --}}
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-200">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user?->name ?? 'Admin') }}&background=123C32&color=fff"
                    class="w-10 h-10 rounded-full" alt="">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $post->user?->name }}</p>
                    <p class="text-xs text-gray-500">Penulis</p>
                </div>
            </div>

            {{-- Featured Image --}}
            @if ($post->featured_image)
                <div class="rounded-2xl overflow-hidden mb-8">
                    <img src="{{ Storage::url($post->featured_image) }}" class="w-full h-auto"
                        alt="{{ $translation?->title }}">
                </div>
            @endif

            {{-- Content --}}
            <div
                class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-primary prose-a:text-accent">
                {!! $translation?->content !!}
            </div>

            {{-- Tags --}}
            @if ($post->tags->count())
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Tags:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span class="px-3 py-1 bg-cream text-primary text-sm rounded-full">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share --}}
            <div class="mt-8 flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">Bagikan:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                    class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($translation?->title) }}"
                    target="_blank"
                    class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                    </svg>
                </a>
                <a href="https://wa.me/?text={{ urlencode($translation?->title . ' ' . url()->current()) }}"
                    target="_blank"
                    class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                </a>
            </div>

        </div>
    </article>

    {{-- Related Posts --}}
    @if ($related->count())
        <section class="py-16 bg-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-heading text-2xl font-bold text-primary mb-8">Berita Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($related as $rel)
                        @php $rtr = $rel->translations->firstWhere('locale', app()->getLocale()) ?? $rel->translations->firstWhere('locale', 'id'); @endphp
                        <a href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'slug' => $rtr?->slug]) }}"
                            class="group bg-white rounded-2xl overflow-hidden hover:shadow-lg transition">
                            <div class="aspect-video bg-gray-100">
                                @if ($rel->featured_image)
                                    <img src="{{ Storage::url($rel->featured_image) }}" class="w-full h-full object-cover"
                                        alt="">
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500 mb-2">{{ $rel->published_at?->format('d M Y') }}</p>
                                <h3
                                    class="font-heading font-semibold text-primary line-clamp-2 group-hover:text-accent transition">
                                    {{ $rtr?->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
