@extends('layouts.frontend')

@section('title', 'Cek Status Aspirasi - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Cek Status Aspirasi</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Masukkan kode tiket yang Anda terima saat mengirim aspirasi.
            </p>
        </div>
    </section>

    <section class="py-16 bg-cream min-h-[60vh]">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm">

                @if ($errors->any())
                    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('frontend.contact.status', ['locale' => app()->getLocale()]) }}" method="POST"
                    class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Tiket <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="ticket_code" value="{{ old('ticket_code') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-mono text-center text-lg tracking-wider focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                            placeholder="ASP-20260101-0001">
                        <p class="text-xs text-gray-500 mt-2">Format: ASP-YYYYMMDD-XXXX</p>
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-full hover:bg-opacity-90 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cek Status
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <a href="{{ route('frontend.contact', ['locale' => app()->getLocale()]) }}"
                        class="text-sm text-gray-600 hover:text-primary">
                        ← Kembali ke Form Kontak
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
