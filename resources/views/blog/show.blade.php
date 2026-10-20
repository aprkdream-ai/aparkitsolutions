@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" :title="$post['title']" :lead="$post['description']">
    <div class="page-hero-meta">
        <span><x-icon name="folder" /><a href="{{ route('blog.category', $categorySlug) }}">{{ $post['category'] }}</a></span>
        <span><x-icon name="calendar" /><time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('F j, Y') }}</time></span>
        @if ($post['updated'])<span>Updated <time datetime="{{ $post['updated'] }}">{{ \Illuminate\Support\Carbon::parse($post['updated'])->format('F j, Y') }}</time></span>@endif
        <span><x-icon name="clock" />{{ $post['reading_time'] }} min read</span>
        <span><x-icon name="users" />{{ $author['name'] }}</span>
    </div>
</x-page-hero>

<section class="section">
    <div class="container detail">
        <article>
            @if ($post['draft'])<p class="notice notice--draft"><x-icon name="alert" /> Draft article. Not visible in production.</p>@endif
            @if ($post['image'])
                <img src="{{ asset($post['image']) }}" alt="{{ $post['image_alt'] ?? $post['title'] }}" width="1600" height="900" fetchpriority="high">
            @endif
            <div class="prose">{!! $post['html'] !!}</div>

            @if ($post['tags'])
                <ul class="chips mt-lg" aria-label="Tags">@foreach ($post['tags'] as $tag)<li class="chip chip--mint">#{{ $tag }}</li>@endforeach</ul>
            @endif

            @include('partials.share')

            <div class="author-box">
                <img class="brand-mark" src="{{ asset('assets/img/brand/apark-mark.png') }}" alt="" width="40" height="40">
                <div><strong>{{ $author['name'] }}</strong>@if ($author['bio'])<p>{{ $author['bio'] }}</p>@endif</div>
            </div>
        </article>

        <aside>
            <div class="aside-card aside-card--teal">
                <h2>Need help with a project?</h2>
                <p>Talk to us about your software, website, or automation needs.</p>
                <a class="btn btn--light btn--block" href="{{ route('contact') }}">Get Expert Advice <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
            @if ($related->isNotEmpty())
                <div class="aside-card">
                    <h2>Related articles</h2>
                    <ul class="aside-links">
                        @foreach ($related as $r)<li><a href="{{ route('blog.show', $r['slug']) }}">{{ $r['title'] }} <x-icon name="arrow-right" /></a></li>@endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</section>

<x-cta />
@endsection
