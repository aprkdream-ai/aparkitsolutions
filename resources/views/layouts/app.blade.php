<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.seo', ['seo' => $seo ?? \App\Support\Seo::make()])
    <link rel="preload" href="{{ asset('assets/fonts/inter-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/montserrat-var.woff2') }}" as="font" type="font/woff2" crossorigin>
    @if (config('site.inline_css'))
        <style>{{ inline_css() }}</style>
    @else
        <link rel="stylesheet" href="{{ asset_v('assets/css/app.min.css') }}">
    @endif
    <link rel="icon" href="{{ asset('assets/img/brand/favicon-48.png') }}" type="image/png" sizes="48x48">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset_v('assets/img/brand/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ route('manifest') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('site.short_name') }}">
    <meta name="application-name" content="{{ config('site.short_name') }}">
    <meta name="theme-color" content="#0F766E">
    <script src="{{ asset_v('assets/js/app.js') }}" defer></script>
    @if (config('site.analytics.ga4') && config('site.indexable'))
        <script src="{{ asset_v('assets/js/analytics.js') }}" data-ga4="{{ config('site.analytics.ga4') }}" defer></script>
    @endif
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    @include('partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @if ($wa = whatsapp_url('Hello APARK IT Solutions, I would like to discuss a project.'))
        <a class="wa-float" href="{{ $wa }}" target="_blank" rel="noopener">
            <x-icon name="whatsapp" /><span class="visually-hidden">Chat with APARK IT Solutions on WhatsApp</span>
        </a>
    @endif
</body>
</html>
