@extends('layouts.frontend')

@section('title', 'Segera Hadir - ' . setting('site_name'))

@section('content')
    <section class="pt-32 pb-20 min-h-screen flex items-center">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="text-8xl mb-6">🚧</div>
            <h1 class="font-heading text-4xl font-bold text-primary mb-4">Halaman Segera Hadir</h1>
            <p class="text-gray-600 mb-8">Halaman ini sedang dalam pengembangan. Silakan kembali lagi nanti.</p>
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-medium rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </section>
@endsection
