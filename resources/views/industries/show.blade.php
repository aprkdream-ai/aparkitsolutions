@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Industry" :title="$industry['title']" :lead="$industry['summary']" />

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
            <div class="detail-section">
                <h2>How we can help</h2>
                <div class="grid grid-features">@foreach ($services as $s)<x-service-card :service="$s" />@endforeach</div>
            </div>
        </div>
        <aside>
            <div class="aside-card aside-card--teal">
                <h2>Talk to us about your {{ \Illuminate\Support\Str::lower($industry['title']) }} project</h2>
                <a class="btn btn--light btn--block" href="{{ route('contact') }}">Start a Project <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
        </aside>
    </div>
</section>

<x-cta />
@endsection
