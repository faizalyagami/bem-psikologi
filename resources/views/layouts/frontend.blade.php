<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Dinamis --}}
    <title>@yield('title', setting('seo_meta_title', setting('site_name')))</title>
    <meta name="description" content="@yield('meta_description', setting('seo_meta_description'))">
    @if (setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @endif
    <meta property="og:title" content="@yield('title', setting('site_name'))">
    <meta property="og:description" content="@yield('meta_description', setting('seo_meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Favicon --}}
    @if (setting('site_favicon'))
        <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif

    {{-- Fonts Dinamis --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', setting('font_heading', 'Plus Jakarta Sans')) }}:wght@400;500;600;700;800&family={{ str_replace(' ', '+', setting('font_body', 'Inter')) }}:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Warna Dinamis dari Setting --}}
    <style>
        :root {
            --color-primary: {{ setting('color_primary', '#123C32') }};
            --color-secondary: {{ setting('color_secondary', '#242B2A') }};
            --color-accent: {{ setting('color_accent', '#D4AD58') }};
            --color-cream: {{ setting('color_cream', '#F5F3ED') }};
            --font-heading: '{{ setting('font_heading', 'Plus Jakarta Sans') }}', sans-serif;
            --font-body: '{{ setting('font_body', 'Inter') }}', sans-serif;
        }

        .font-heading {
            font-family: var(--font-heading);
        }

        .font-body {
            font-family: var(--font-body);
        }

        body {
            font-family: var(--font-body);
        }

        .text-primary {
            color: var(--color-primary);
        }

        .text-secondary {
            color: var(--color-secondary);
        }

        .text-accent {
            color: var(--color-accent);
        }

        .bg-primary {
            background-color: var(--color-primary);
        }

        .bg-secondary {
            background-color: var(--color-secondary);
        }

        .bg-accent {
            background-color: var(--color-accent);
        }

        .bg-cream {
            background-color: var(--color-cream);
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased font-body">

    {{-- Header --}}
    @include('frontend.partials.header')

    {{-- Konten --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('frontend.partials.footer')

    @stack('scripts')
</body>

</html>
