@extends('layouts.frontend')
@section('title', 'Program Kerja - ' . setting('site_name'))
@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Program Kerja</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Program dan kegiatan BEM Fakultas Psikologi.</p>
        </div>
    </section>

    <section class="py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="department" class="px-4 py-3 bg-white border border-gray-200 rounded-full focus:outline-none">
                    <option value="">Semua Departemen</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->slug }}" @selected(request('department') === $d->slug)>{{ $d->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-3 bg-white border border-gray-200 rounded-full focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="planned" @selected(request('status') === 'planned')>Direncanakan</option>
                    <option value="ongoing" @selected(request('status') === 'ongoing')>Berjalan</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                </select>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-full">Filter</button>
            </form>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($programs->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($programs as $program)
                        @php
                            $statusLabel = match ($program->status) {
                                'planned' => ['Direncanakan', 'bg-yellow-100 text-yellow-700'],
                                'ongoing' => ['Berjalan', 'bg-blue-100 text-blue-700'],
                                'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                                'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700'],
                            };
                        @endphp
                        <a href="{{ route('frontend.programs.show', ['locale' => app()->getLocale(), 'slug' => $program->slug]) }}"
                            class="group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition">
                            <div class="aspect-video bg-gray-100 relative">
                                @if ($program->featured_image)
                                    <img src="{{ Storage::url($program->featured_image) }}"
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
                                <span
                                    class="absolute top-3 left-3 text-xs px-2 py-0.5 rounded-full {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
                            </div>
                            <div class="p-6">
                                <p class="text-xs text-accent font-semibold">{{ $program->department?->name ?? 'Umum' }}</p>
                                <h3
                                    class="font-heading font-bold text-lg text-primary mt-1 line-clamp-2 group-hover:text-accent transition">
                                    {{ $program->title }}</h3>
                                <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                    {{ Str::limit($program->description, 120) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $programs->links() }}</div>
            @else
                <div class="text-center py-20">
                    <p class="text-gray-500 text-lg">Belum ada program kerja.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
