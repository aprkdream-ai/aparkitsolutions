@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Industry" :title="$industry['title']" :lead="$industry['summary']">
    <div class="btn-row"><a class="btn btn--primary" href="{{ route('contact') }}">Talk to Our Team <x-icon name="arrow-right" class="icon-arrow" /></a></div>
    <x-slot:aside>
        <div class="glass">
            <div class="hero-feature">
                <span class="icon-tile icon-tile--glass"><x-icon :name="$industry['icon']" /></span>
                <div><strong>{{ $projects->count() }} {{ \Illuminate\Support\Str::plural('project', $projects->count()) }} delivered</strong><span>in {{ $industry['title'] }}</span></div>
            </div>
            <p class="glass-label">Relevant services</p>
            <ul class="hero-links">@foreach ($services as $s)<li><a href="{{ route('services.show', $s['slug']) }}"><x-icon :name="$s['icon']" />{{ $s['title'] }}</a></li>@endforeach</ul>
        </div>
    </x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container detail">
        <div>
            @if ($industry['draft'])
                <p class="notice notice--draft"><x-icon name="alert" /> Draft entry. Publish only if APARK actively serves this industry.</p>
            @endif
            <div class="detail-section prose"><p>{{ $industry['intro'] }}</p></div>
            <div class="detail-section">
                <h2>Common challenges</h2>
                <ul class="check-list">@foreach ($industry['challenges'] as $c)<li><x-icon name="check-circle" /><span>{{ $c }}</span></li>@endforeach</ul>
            </div>
            @if ($projects->isNotEmpty())
                <div class="detail-section">
                    <h2>Projects we have delivered</h2>
                    <div class="grid grid-features">@foreach ($projects as $p)<x-project-card :project="$p" />@endforeach</div>
                </div>
            @endif
            <div class="detail-section">
                <h2>How we can help</h2>
                <div class="grid grid-features">@foreach ($services as $s)<x-service-card :service="$s" />@endforeach</div>
            </div>
        </div>
        <aside>
            <div class="aside-card aside-card--teal">
                <h2>Talk to us about your {{ \Illuminate\Support\Str::lower($industry['title']) }} project</h2>
                <a class="btn btn--light btn--block" href="{{ route('contact') }}">Plan Your Project <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
        </aside>
    </div>
</section>

<x-cta />
@endsection
