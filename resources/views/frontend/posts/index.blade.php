@extends('layouts.frontend')

@section('title', 'Berita & Artikel - ' . setting('site_name'))
@section('meta_description', 'Berita, artikel, dan informasi terbaru dari ' . setting('site_name'))

@section('content')

    {{-- Page Header --}}
    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Berita & Artikel</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Informasi terbaru seputar kegiatan dan program BEM Fakultas
                Psikologi.</p>
        </div>
    </section>

    {{-- Filter --}}
    <section class="py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-full focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>
                <select name="category"
                    class="px-4 py-3 bg-white border border-gray-200 rounded-full focus:border-primary focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-6 py-3 bg-primary text-white font-medium rounded-full hover:bg-opacity-90">Filter</button>
            </form>
        </div>
    </section>

    {{-- Posts Grid --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($posts->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        @php
                            $tr =
                                $post->translations->firstWhere('locale', app()->getLocale()) ??
                                $post->translations->firstWhere('locale', 'id');
                        @endphp
                        <article
                            class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition">
                            <a
                                href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}">
                                <div class="aspect-video overflow-hidden bg-gray-100">
                                    @if ($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            alt="">
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
                                        href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'slug' => $tr?->slug]) }}">{{ $tr?->title }}</a>
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-3">
                                    {{ Str::limit(strip_tags($tr?->excerpt ?? $tr?->content), 150) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12">{{ $posts->links() }}</div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada berita.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
