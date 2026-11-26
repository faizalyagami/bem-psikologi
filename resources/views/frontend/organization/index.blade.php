@extends('layouts.frontend')

@section('title', 'Struktur Organisasi - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Struktur Organisasi</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Kabinet BEM Fakultas Psikologi Unisba.</p>
        </div>
    </section>

    {{-- Pengurus Inti --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-accent font-semibold text-sm uppercase tracking-wider">Pimpinan</span>
                <h2 class="font-heading text-3xl font-bold text-primary mt-2">Pengurus Inti</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($pimpinan as $member)
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center hover:shadow-xl transition">
                        <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                            class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-cream">
                        <h3 class="font-heading font-bold text-primary">{{ $member->name }}</h3>
                        <p class="text-sm text-accent font-medium mt-1">{{ $member->position->name ?? '—' }}</p>
                        @if ($member->instagram)
                            <a href="https://instagram.com/{{ $member->instagram }}" target="_blank"
                                class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-primary mt-3">
                                <i data-lucide="instagram" class="w-3.5 h-3.5"></i> {{ '@' . $member->instagram }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Departemen --}}
    <section class="py-16 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-accent font-semibold text-sm uppercase tracking-wider">Departemen</span>
                <h2 class="font-heading text-3xl font-bold text-primary mt-2">Kementerian & Departemen</h2>
            </div>

            <div class="space-y-8">
                @foreach ($departments as $dept)
                    <div class="bg-white rounded-2xl p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <i data-lucide="{{ $dept->icon ?? 'folder' }}" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading text-xl font-bold text-primary">{{ $dept->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $dept->description }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                            @foreach ($dept->members as $member)
                                <div class="text-center">
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                                        class="w-16 h-16 rounded-full mx-auto mb-2 object-cover">
                                    <p class="text-sm font-medium text-primary">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->position->name ?? '—' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
