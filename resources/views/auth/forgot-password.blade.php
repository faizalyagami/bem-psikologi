@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

    <div class="mb-8">
        <h2 class="font-heading text-3xl font-bold text-white mb-2">Lupa Password? 🔒</h2>
        <p class="text-gray-400">Masukkan email Anda, kami akan mengirimkan tautan reset password.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="admin-label">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="w-5 h-5 text-gray-500"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="admin-input pl-10 @error('email') border-red-500 @enderror"
                    placeholder="admin@bem-psikologi.test">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-lg transition shadow-lg shadow-brand-900/50">
            <i data-lucide="send" class="w-5 h-5"></i>
            Kirim Tautan Reset
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-dark-border text-center text-sm">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Halaman Login
        </a>
    </div>

@endsection
