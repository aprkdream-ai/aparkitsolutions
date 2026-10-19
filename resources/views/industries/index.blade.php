@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Industries" title="Industries we serve"
    lead="Technology works best when it fits the realities of your sector. Here is how we help the industries we work with." />

<section class="section">
    <div class="container">
        @if ($industries->contains('draft', true))
            <p class="notice notice--draft"><x-icon name="alert" /> Draft entries are visible because CONTENT_SHOW_DRAFTS is enabled. Publish only the industries APARK actually serves.</p>
        @endif
        <h2 class="visually-hidden">Industries</h2>
        <div class="grid grid-3">
            @foreach ($industries as $industry)
                <article class="card card--hover">
                    <span class="icon-tile"><x-icon :name="$industry['icon']" /></span>
                    <h3><a href="{{ route('industries.show', $industry['slug']) }}">{{ $industry['title'] }}</a></h3>
                    <p>{{ $industry['summary'] }}</p>
                    <span class="link-arrow" aria-hidden="true">Learn more <x-icon name="arrow-right" /></span>
                </article>
            @endforeach
        </div>
    </div>
</section>

<x-cta />
@endsection
