@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" :eyebrow="$project['category']" :title="$project['title']" :lead="$project['summary']">
    @if ($project['url'])
        <div class="btn-row"><a class="btn btn--primary" href="{{ $project['url'] }}" target="_blank" rel="noopener">Visit live project <x-icon name="external" /></a></div>
    @endif
</x-page-hero>

<section class="section">
    <div class="container detail">
        <div>
            @if ($project['draft'])
                <p class="notice notice--draft"><x-icon name="alert" /> Sample draft entry. Replace it with a verified case study before publishing.</p>
            @endif
            @if ($project['image'])
                <img class="detail-section" src="{{ asset($project['image']) }}" alt="{{ $project['image_alt'] ?? $project['title'] }}" width="1600" height="1000" fetchpriority="high">
            @endif
            <div class="detail-section prose"><h2>Overview</h2><p>{{ $project['overview'] }}</p></div>
            <div class="detail-section prose"><h2>The challenge</h2><p>{{ $project['challenge'] }}</p></div>
            <div class="detail-section prose"><h2>Our solution</h2><p>{{ $project['solution'] }}</p></div>
            @if (! empty($project['results']))
                <div class="detail-section">
                    <h2>Results</h2>
                    <ul class="check-list">@foreach ($project['results'] as $r)<li><x-icon name="check-circle" /><span>{{ $r }}</span></li>@endforeach</ul>
                </div>
            @endif
        </div>
        <aside>
            <div class="aside-card">
                <h2>Project details</h2>
                <dl class="dl">
                    @if ($project['client'])<div><dt>Client</dt><dd>{{ $project['client'] }}</dd></div>@endif
                    <div><dt>Category</dt><dd>{{ $project['category'] }}</dd></div>
                    @if ($services->isNotEmpty())
                        <div><dt>Services</dt><dd>@foreach ($services as $s)<a href="{{ route('services.show', $s['slug']) }}">{{ $s['title'] }}</a>@unless ($loop->last), @endunless @endforeach</dd></div>
                    @endif
                    @if (! empty($project['tech']))
                        <div><dt>Technology</dt><dd><ul class="chips mt-0">@foreach ($project['tech'] as $t)<li class="chip">{{ $t }}</li>@endforeach</ul></dd></div>
                    @endif
                </dl>
            </div>
        </aside>
    </div>
</section>

@if ($more->isNotEmpty())
<section class="section section--off">
    <div class="container">
        <div class="section-head"><h2>More projects</h2></div>
        <div class="grid grid-3">@foreach ($more as $p)<x-project-card :project="$p" />@endforeach</div>
    </div>
</section>
@endif

<x-cta />
@endsection
