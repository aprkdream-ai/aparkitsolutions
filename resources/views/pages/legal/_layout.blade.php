@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Legal" :title="$title">
    <div class="page-hero-meta"><span><x-icon name="calendar" />Last updated: <time datetime="{{ config('site.legal_updated') }}">{{ \Illuminate\Support\Carbon::parse(config('site.legal_updated'))->format('F j, Y') }}</time></span></div>
</x-page-hero>
<section class="section">
    <div class="container container--narrow">
        @unless (config('site.legal_reviewed'))
            <p class="notice notice--draft"><x-icon name="alert" /> Template pending business and legal review. This page is excluded from search indexing until LEGAL_REVIEWED=true.</p>
        @endunless
        <div class="prose">@yield('legal')</div>
    </div>
</section>
@endsection
