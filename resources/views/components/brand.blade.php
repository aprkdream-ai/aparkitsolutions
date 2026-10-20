@props(['footer' => false])
{{-- The logo's alt text is the link text (unique per placement, never empty). --}}
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'brand']) }}>
    @if ($footer)
        <img class="brand-logo brand-logo--dark" src="{{ asset('assets/img/brand/logo-dark.webp') }}" alt="{{ config('site.name') }} — {{ config('site.slogans.0') }}" width="532" height="146" loading="lazy" decoding="async">
    @else
        <img class="brand-logo" src="{{ asset(config('site.logo') ?: 'assets/img/brand/logo-compact.webp') }}" alt="{{ config('site.name') }} logo — home" width="349" height="61">
    @endif
</a>
