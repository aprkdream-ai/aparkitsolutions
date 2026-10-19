<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo', ['seo' => $seo ?? \App\Support\Seo::make()])
    <link rel="preload" href="{{ asset('assets/fonts/inter-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/montserrat-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset_v('assets/css/app.min.css') }}">
    <link rel="icon" href="{{ asset('assets/img/brand/favicon-48.png') }}" type="image/png" sizes="48x48">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/brand/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#0F766E">
    <script src="{{ asset_v('assets/js/app.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    @include('partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @if ($wa = whatsapp_url('Hello APARK IT Solutions, I would like to discuss a project.'))
        <a class="wa-float" href="{{ $wa }}" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
            <x-icon name="whatsapp" />
        </a>
    @endif
</body>
</html>
