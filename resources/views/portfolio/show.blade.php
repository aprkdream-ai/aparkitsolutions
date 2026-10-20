@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" :eyebrow="$project['category']" :title="$project['title']" :lead="$project['summary']">
    @if ($project['url'])
        <div class="btn-row"><a class="btn btn--primary" href="{{ $project['url'] }}" target="_blank" rel="noopener">Visit website <x-icon name="external" /></a></div>
    @endif
    @if (! empty($project['tech']))
        <ul class="chips mt-lg">@foreach (array_slice($project['tech'], 0, 6) as $t)<li class="chip">{{ $t }}</li>@endforeach</ul>
    @endif
    <x-slot:aside>
        @if ($project['image'])
            <div class="browser-frame">
                <div class="browser-bar" aria-hidden="true"><i></i><i></i><i></i><span>{{ $project['url'] ? preg_replace('#^https?://(www\.)?|/$#', '', $project['url']) : $project['title'] }}</span></div>
                <img src="{{ asset($project['image']) }}" alt="{{ $project['image_alt'] }}" width="1280" height="800" fetchpriority="high">
            </div>
        @else
            <div class="glass">
                <div class="hero-feature">
                    <span class="icon-tile icon-tile--glass"><x-icon name="layout" /></span>
                    <div><strong>{{ $project['title'] }}</strong><span>{{ $project['category'] }}</span></div>
                </div>
                <p class="glass-label">Highlights</p>
                <ul class="hero-links">@foreach (array_slice($project['features'] ?? [], 0, 3) as $f)<li><a href="#features"><x-icon name="check-circle" />{{ $f }}</a></li>@endforeach</ul>
            </div>
        @endif
    </x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container detail">
        <div>
            @if ($project['draft'])
                <p class="notice notice--draft"><x-icon name="alert" /> Draft entry. Not visible in production.</p>
            @endif
            <div class="detail-section prose"><h2>Overview</h2><p>{{ $project['overview'] }}</p></div>
            @if (! empty($project['challenge']))
                <div class="detail-section prose"><h2>The challenge</h2><p>{{ $project['challenge'] }}</p></div>
            @endif
            @if (! empty($project['solution']))
                <div class="detail-section prose"><h2>Our solution</h2><p>{{ $project['solution'] }}</p></div>
            @endif
            @if (! empty($project['features']))
                <div class="detail-section" id="features">
                    <h2>Key features</h2>
                    <ul class="check-list check-list--2">@foreach ($project['features'] as $f)<li><x-icon name="check-circle" /><span>{{ $f }}</span></li>@endforeach</ul>
                </div>
            @endif
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
                    <div><dt>Category</dt><dd><a href="{{ route('portfolio.index', ['category' => \Illuminate\Support\Str::slug($project['category'])]) }}">{{ $project['category'] }}</a></dd></div>
                    @if ($industry)
                        <div><dt>Industry</dt><dd><a href="{{ route('industries.show', $industry['slug']) }}">{{ $industry['title'] }}</a></dd></div>
                    @endif
                    @if ($services->isNotEmpty())
                        <div><dt>Services</dt><dd>@foreach ($services as $s)<a href="{{ route('services.show', $s['slug']) }}">{{ $s['title'] }}</a>@unless ($loop->last), @endunless @endforeach</dd></div>
                    @endif
                    @if (! empty($project['tech']))
                        <div><dt>Technology</dt><dd><ul class="chips mt-0">@foreach ($project['tech'] as $t)<li class="chip">{{ $t }}</li>@endforeach</ul></dd></div>
                    @endif
                    @if ($project['url'])
                        <div><dt>Website</dt><dd><a href="{{ $project['url'] }}" target="_blank" rel="noopener">{{ preg_replace('#^https?://(www\.)?|/$#', '', $project['url']) }}</a></dd></div>
                    @endif
                </dl>
            </div>
            <div class="aside-card aside-card--teal">
                <h2>Need something similar?</h2>
                <p>Tell us about your requirements and we will suggest the right approach.</p>
                <a class="btn btn--light btn--block" href="{{ route('contact', ['service' => $project['services'][0] ?? null]) }}">Build Something Similar <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
        </aside>
    </div>
</section>

@if ($more->isNotEmpty())
<section class="section section--off">
    <div class="container">
        <div class="section-head section-head--row">
            <div><h2>More {{ \Illuminate\Support\Str::lower($project['category']) }}</h2></div>
            <a class="btn btn--outline" href="{{ route('portfolio.index') }}">All projects <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="grid grid-3">@foreach ($more as $p)<x-project-card :project="$p" />@endforeach</div>
    </div>
</section>
@endif

<x-cta />
@endsection
