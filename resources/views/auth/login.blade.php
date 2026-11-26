@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')

    <div class="mb-8">
        <h2 class="font-heading text-3xl font-bold text-white mb-2">Selamat Datang 👋</h2>
        <p class="text-gray-400">Silakan masuk ke akun CMS Anda.</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    {{-- Error Umum --}}
    @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 text-red-400 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="admin-label">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="w-5 h-5 text-gray-500"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" class="admin-input pl-10 @error('email') border-red-500 @enderror"
                    placeholder="admin@bem-psikologi.test">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="admin-label">Password</label>
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="lock" class="w-5 h-5 text-gray-500"></i>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                    autocomplete="current-password"
                    class="admin-input pl-10 pr-10 @error('password') border-red-500 @enderror" placeholder="••••••••">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300">
                    <i data-lucide="eye" x-show="!show" class="w-5 h-5"></i>
                    <i data-lucide="eye-off" x-show="show" x-cloak class="w-5 h-5"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember & Forgot --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded bg-dark-bg border-dark-border text-brand-600 focus:ring-brand-600 focus:ring-offset-dark-bg">
                <span class="ml-2 text-sm text-gray-400">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-brand-400 hover:text-brand-300 transition">
                    Lupa password?
                </a>
            @endif
        </div>

        {{-- Tombol Login --}}
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-lg transition shadow-lg shadow-brand-900/50">
            <i data-lucide="log-in" class="w-5 h-5"></i>
            Masuk ke Dashboard
        </button>
    </form>

    {{-- Footer Link --}}
    <div class="mt-8 pt-6 border-t border-dark-border text-center text-sm text-gray-500">
        <a href="{{ route('home', ['locale' => 'id']) }}"
            class="inline-flex items-center gap-1 text-gray-400 hover:text-white transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Beranda
        </a>
    </div>

@endsection
