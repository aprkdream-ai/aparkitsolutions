@props(['seo', 'eyebrow' => null, 'title', 'lead' => null])
<section class="page-hero">
    <div class="container">
        @include('partials.breadcrumbs', ['seo' => $seo])
        @if ($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
        <h1>{{ $title }}</h1>
        @if ($lead)<p class="lead">{{ $lead }}</p>@endif
        {{ $slot }}
    </div>
</section>
