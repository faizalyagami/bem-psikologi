@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')

    <x-admin.page-header title="Pengaturan Website"
        description="Kelola identitas, branding, SEO, email, dan footer situs."></x-admin.page-header>

    @if (session('success'))
        <div
            class="mb-4 px-4 py-3 bg-green-900/20 border border-green-900/40 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    @endif

    <div x-data="{ tab: 'identity' }" class="space-y-5">

        {{-- Tab Navigation --}}
        <div class="admin-card p-2 flex flex-wrap gap-1">
            @php
                $tabs = [
                    'identity' => ['Identitas', 'building-2'],
                    'branding' => ['Branding', 'palette'],
                    'seo' => ['SEO', 'search'],
                    'email' => ['Email', 'mail'],
                    'footer' => ['Footer', 'panel-bottom'],
                ];
            @endphp
            @foreach ($tabs as $key => [$label, $icon])
                <button @click="tab='{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'bg-brand-900/40 text-brand-300' :
                        'text-gray-400 hover:bg-dark-hover'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4"></i> {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- ============= TAB IDENTITAS ============= --}}
        <div x-show="tab==='identity'" x-cloak>
            <form action="{{ route('admin.settings.update-identity') }}" method="POST" enctype="multipart/form-data"
                class="admin-card p-6 space-y-5 max-w-3xl">
                @csrf @method('PUT')
                <div>
                    <label class="admin-label">Nama Situs</label>
                    <input type="text" name="site_name" value="{{ setting('site_name') }}" class="admin-input" required>
                </div>
                <div>
                    <label class="admin-label">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ setting('site_tagline') }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Deskripsi</label>
                    <textarea name="site_description" rows="3" class="admin-input">{{ setting('site_description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Logo</label>
                        @if (setting('site_logo'))
                            <img src="{{ Storage::url(setting('site_logo')) }}" class="h-16 mb-2 bg-white p-1 rounded">
                        @endif
                        <input type="file" name="site_logo" accept="image/*" class="admin-input text-xs">
                    </div>
                    <div>
                        <label class="admin-label">Favicon</label>
                        @if (setting('site_favicon'))
                            <img src="{{ Storage::url(setting('site_favicon')) }}"
                                class="h-8 w-8 mb-2 bg-white p-1 rounded">
                        @endif
                        <input type="file" name="site_favicon" accept="image/*" class="admin-input text-xs">
                    </div>
                </div>
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                    Identitas</button>
            </form>
        </div>

        {{-- ============= TAB BRANDING ============= --}}
        <div x-show="tab==='branding'" x-cloak>
            <form action="{{ route('admin.settings.update-branding') }}" method="POST"
                class="admin-card p-6 space-y-5 max-w-3xl">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach (['color_primary' => 'Primary', 'color_secondary' => 'Secondary', 'color_accent' => 'Accent', 'color_cream' => 'Cream'] as $key => $label)
                        <div>
                            <label class="admin-label">{{ $label }}</label>
                            <input type="color" name="{{ $key }}" value="{{ setting($key, '#000000') }}"
                                class="w-full h-12 bg-dark-surface border border-dark-border rounded cursor-pointer">
                            <p class="text-[10px] text-gray-500 mt-1 font-mono">{{ setting($key) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Font Heading</label>
                        <select name="font_heading" class="admin-input">
                            @foreach (['Plus Jakarta Sans', 'Poppins', 'Inter', 'Roboto', 'Montserrat'] as $f)
                                <option value="{{ $f }}" @selected(setting('font_heading') === $f)>{{ $f }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Font Body</label>
                        <select name="font_body" class="admin-input">
                            @foreach (['Inter', 'Roboto', 'Open Sans', 'Nunito', 'Lato'] as $f)
                                <option value="{{ $f }}" @selected(setting('font_body') === $f)>{{ $f }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-4 bg-dark-surface rounded-lg border border-dark-border">
                    <p class="text-xs text-gray-400 mb-2">Preview:</p>
                    <p class="text-xl font-bold" style="color: {{ setting('color_primary') }}">BEM Psikologi Unisba</p>
                    <p class="text-sm" style="color: {{ setting('color_secondary') }}">Website resmi BEM Fakultas
                        Psikologi.</p>
                </div>
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                    Branding</button>
            </form>
        </div>

        {{-- ============= TAB SEO ============= --}}
        <div x-show="tab==='seo'" x-cloak>
            <form action="{{ route('admin.settings.update-seo') }}" method="POST" enctype="multipart/form-data"
                class="admin-card p-6 space-y-5 max-w-3xl">
                @csrf @method('PUT')
                <div>
                    <label class="admin-label">Meta Title</label>
                    <input type="text" name="seo_meta_title" value="{{ setting('seo_meta_title') }}" class="admin-input"
                        maxlength="255">
                </div>
                <div>
                    <label class="admin-label">Meta Description</label>
                    <textarea name="seo_meta_description" rows="3" class="admin-input" maxlength="500">{{ setting('seo_meta_description') }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Open Graph Image</label>
                    @if (setting('seo_og_image'))
                        <img src="{{ Storage::url(setting('seo_og_image')) }}" class="h-32 mb-2 rounded">
                    @endif
                    <input type="file" name="seo_og_image" accept="image/*" class="admin-input text-xs">
                </div>
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                    SEO</button>
            </form>
        </div>

        {{-- ============= TAB EMAIL ============= --}}
        <div x-show="tab==='email'" x-cloak>
            <form action="{{ route('admin.settings.update-email') }}" method="POST"
                class="admin-card p-6 space-y-5 max-w-3xl">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="admin-label">SMTP Host</label><input type="text" name="mail_host"
                            value="{{ setting('mail_host') }}" class="admin-input"></div>
                    <div><label class="admin-label">Port</label><input type="text" name="mail_port"
                            value="{{ setting('mail_port') }}" class="admin-input"></div>
                    <div><label class="admin-label">Username</label><input type="text" name="mail_username"
                            value="{{ setting('mail_username') }}" class="admin-input"></div>
                    <div><label class="admin-label">Password</label><input type="password" name="mail_password"
                            value="{{ setting('mail_password') }}" class="admin-input"></div>
                    <div>
                        <label class="admin-label">Encryption</label>
                        <select name="mail_encryption" class="admin-input">
                            <option value="tls" @selected(setting('mail_encryption') === 'tls')>TLS</option>
                            <option value="ssl" @selected(setting('mail_encryption') === 'ssl')>SSL</option>
                        </select>
                    </div>
                    <div><label class="admin-label">From Name</label><input type="text" name="mail_from_name"
                            value="{{ setting('mail_from_name') }}" class="admin-input"></div>
                    <div class="sm:col-span-2"><label class="admin-label">From Address</label><input type="email"
                            name="mail_from_address" value="{{ setting('mail_from_address') }}" class="admin-input">
                    </div>
                </div>
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                    Email</button>
            </form>
        </div>

        {{-- ============= TAB FOOTER ============= --}}
        <div x-show="tab==='footer'" x-cloak>
            <form action="{{ route('admin.settings.update-footer') }}" method="POST"
                class="admin-card p-6 space-y-5 max-w-3xl">
                @csrf @method('PUT')
                <div>
                    <label class="admin-label">Deskripsi Footer</label>
                    <textarea name="footer_description" rows="3" class="admin-input">{{ setting('footer_description') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2"><label class="admin-label">Alamat</label><input type="text"
                            name="footer_address" value="{{ setting('footer_address') }}" class="admin-input"></div>
                    <div><label class="admin-label">Telepon</label><input type="text" name="footer_phone"
                            value="{{ setting('footer_phone') }}" class="admin-input"></div>
                    <div><label class="admin-label">Email</label><input type="email" name="footer_email"
                            value="{{ setting('footer_email') }}" class="admin-input"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="admin-label">Instagram URL</label><input type="url" name="footer_instagram"
                            value="{{ setting('footer_instagram') }}" class="admin-input"></div>
                    <div><label class="admin-label">Facebook URL</label><input type="url" name="footer_facebook"
                            value="{{ setting('footer_facebook') }}" class="admin-input"></div>
                    <div><label class="admin-label">Twitter/X URL</label><input type="url" name="footer_twitter"
                            value="{{ setting('footer_twitter') }}" class="admin-input"></div>
                    <div><label class="admin-label">YouTube URL</label><input type="url" name="footer_youtube"
                            value="{{ setting('footer_youtube') }}" class="admin-input"></div>
                </div>
                <button type="submit" class="btn-primary"><i data-lucide="save" class="w-4 h-4"></i> Simpan
                    Footer</button>
            </form>
        </div>

    </div>

@endsection
