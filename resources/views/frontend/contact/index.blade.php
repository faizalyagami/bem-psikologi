@extends('layouts.frontend')

@section('title', 'Kontak & Aspirasi - ' . setting('site_name'))

@section('content')

    <section class="pt-32 pb-16 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-heading text-4xl sm:text-5xl font-bold text-white mb-4">Kontak & Aspirasi</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">Sampaikan aspirasi, saran, atau pertanyaan Anda untuk BEM
                Fakultas Psikologi.</p>
        </div>
    </section>

    <section class="py-16 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Info Kontak --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl p-6">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <h3 class="font-heading font-semibold text-primary mb-1">Alamat</h3>
                        <p class="text-sm text-gray-600">{{ setting('footer_address') }}</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="font-heading font-semibold text-primary mb-1">Telepon</h3>
                        <p class="text-sm text-gray-600">{{ setting('footer_phone') }}</p>
                    </div>

                    <div class="bg-white rounded-2xl p-6">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-heading font-semibold text-primary mb-1">Email</h3>
                        <p class="text-sm text-gray-600">{{ setting('footer_email') }}</p>
                    </div>
                </div>

                {{-- Form Aspirasi --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8">
                    <h2 class="font-heading text-2xl font-bold text-primary mb-6">Kirim Aspirasi</h2>

                    @if (session('success'))
                        <div
                            class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('frontend.contact.store', ['locale' => app()->getLocale()]) }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subjek <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                placeholder="Misal: Saran untuk kegiatan">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pesan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                placeholder="Tulis aspirasi Anda...">{{ old('message') }}</textarea>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            Kirim sebagai anonim (nama & email tidak dipublikasikan)
                        </label>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-primary text-white font-semibold rounded-full hover:bg-opacity-90 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Aspirasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
