@props(['footer' => false])
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'brand']) }}>
    @if ($footer)
        <img class="brand-logo brand-logo--dark" src="{{ asset('assets/img/brand/logo-dark.webp') }}" alt="" width="532" height="146" loading="lazy" decoding="async">
        <span class="visually-hidden">{{ config('site.name') }} home page</span>
    @else
        <img class="brand-logo" src="{{ asset(config('site.logo') ?: 'assets/img/brand/logo-compact.webp') }}" alt="" width="349" height="61">
        <span class="visually-hidden">{{ config('site.name') }} — Home</span>
    @endif
</a>
