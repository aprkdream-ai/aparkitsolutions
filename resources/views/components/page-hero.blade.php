@props(['seo', 'eyebrow' => null, 'title', 'lead' => null])
<section {{ $attributes->merge(['class' => 'page-hero']) }}>
    <svg class="page-hero-arc" viewBox="0 0 600 600" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="ph-arc" x1="0" y1="1" x2="1" y2="0"><stop offset="0" stop-color="#CDEDE8" stop-opacity="0"/><stop offset=".55" stop-color="#5FD0C3" stop-opacity=".55"/><stop offset="1" stop-color="#CDEDE8" stop-opacity=".9"/></linearGradient>
        </defs>
        <circle cx="330" cy="300" r="250" fill="none" stroke="#fff" stroke-opacity=".06" stroke-width="60"/>
        <circle cx="330" cy="300" r="170" fill="none" stroke="#fff" stroke-opacity=".08" stroke-dasharray="2 10"/>
        <path d="M40 520 C 180 360, 360 260, 590 230" fill="none" stroke="url(#ph-arc)" stroke-width="26" stroke-linecap="round"/>
        <path d="M120 560 C 250 430, 400 350, 590 320" fill="none" stroke="#fff" stroke-opacity=".07" stroke-width="10" stroke-linecap="round"/>
        <circle cx="590" cy="230" r="7" fill="#CDEDE8"/>
    </svg>
    <div class="container page-hero-grid {{ isset($aside) && $aside->isNotEmpty() ? 'has-aside' : '' }}">
        <div class="page-hero-text">
            @include('partials.breadcrumbs', ['seo' => $seo])
            @if ($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
            <h1>{{ $title }}</h1>
            @if ($lead)<p class="lead">{{ $lead }}</p>@endif
            {{ $slot }}
        </div>
        @if (isset($aside) && $aside->isNotEmpty())
            <div class="page-hero-aside">{{ $aside }}</div>
        @endif
    </div>
</section>
