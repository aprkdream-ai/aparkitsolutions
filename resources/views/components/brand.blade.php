@props(['footer' => false])
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'brand']) }}>
    @if ($footer)
        <img class="brand-logo brand-logo--dark" src="{{ asset('assets/img/brand/logo-dark.webp') }}" alt="{{ config('site.name') }}" width="532" height="146" loading="lazy" decoding="async">
    @else
        <img class="brand-logo" src="{{ asset(config('site.logo') ?: 'assets/img/brand/logo-compact.webp') }}" alt="{{ config('site.name') }} — Home" width="349" height="61">
    @endif
</a>
