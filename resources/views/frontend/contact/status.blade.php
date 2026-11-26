@extends('layouts.frontend')

@section('title', 'Status Aspirasi - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Status Aspirasi</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Kode Tiket: <span
                    class="font-mono font-bold text-accent">{{ $aspiration->ticket_code }}</span></p>
        </div>
    </section>

    <section class="py-16 bg-cream">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Status Badge --}}
            <div class="bg-white rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Status Saat Ini</p>
                    @php
                        $badge = match ($aspiration->status) {
                            'pending' => [
                                'label' => 'Menunggu Diproses',
                                'class' => 'bg-yellow-100 text-yellow-700',
                                'icon' => 'clock',
                            ],
                            'processing' => [
                                'label' => 'Sedang Diproses',
                                'class' => 'bg-blue-100 text-blue-700',
                                'icon' => 'loader',
                            ],
                            'replied' => [
                                'label' => 'Sudah Dibalas',
                                'class' => 'bg-green-100 text-green-700',
                                'icon' => 'check-circle',
                            ],
                            'closed' => [
                                'label' => 'Ditutup',
                                'class' => 'bg-gray-100 text-gray-600',
                                'icon' => 'lock',
                            ],
                        };
                    @endphp
                    <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold {{ $badge['class'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
                <div class="text-right text-xs text-gray-500">
                    <p>Dikirim: {{ $aspiration->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            {{-- Pesan Asli --}}
            <div class="bg-white rounded-2xl p-6">
                <h2 class="font-heading text-xl font-bold text-primary mb-2">{{ $aspiration->subject }}</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Dari {{ $aspiration->is_anonymous ? 'Anonim' : $aspiration->name }}
                    · {{ $aspiration->created_at->format('d M Y') }}
                </p>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($aspiration->message)) !!}
                </div>
            </div>

            {{-- Balasan dari BEM --}}
            <div class="bg-white rounded-2xl p-6">
                <h3 class="font-heading font-semibold text-primary mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Balasan dari BEM
                </h3>

                @forelse($aspiration->replies as $reply)
                    <div class="p-4 bg-cream rounded-xl mb-3">
                        <div class="flex items-center gap-2 mb-2">
                            <div
                                class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-primary">{{ $reply->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">{{ $reply->message }}</p>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Aspirasi Anda sedang menunggu diproses. Mohon tunggu balasan dari
                            tim BEM.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center">
                <a href="{{ route('frontend.contact.check', ['locale' => app()->getLocale()]) }}"
                    class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-primary">
                    ← Cek Kode Tiket Lain
                </a>
            </div>
        </div>
    </section>

@endsection
