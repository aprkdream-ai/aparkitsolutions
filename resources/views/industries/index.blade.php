@extends('layouts.app')

@section('content')
@php($allProjects = \App\Support\Content::projects())
<x-page-hero :seo="$seo" eyebrow="Industries" title="Industries we serve"
    lead="Technology works best when it fits the realities of your sector. Here is how we help the industries we work with.">
    <x-slot:aside>
        <nav class="glass" aria-label="Industries">
            <p class="glass-label">{{ $industries->count() }} industries</p>
            <div class="icon-grid">
                @foreach ($industries->take(9) as $i)
                    <a href="{{ route('industries.show', $i['slug']) }}"><x-icon :name="$i['icon']" />{{ \Illuminate\Support\Str::before($i['title'], ' &') }}</a>
                @endforeach
            </div>
        </nav>
    </x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container">
        <h2 class="visually-hidden">Industries</h2>
        <div class="grid grid-2">
            @foreach ($industries as $industry)
                @php($items = $allProjects->where('industry', $industry['slug']))
                <article class="card card--hover industry-card">
                    <div class="industry-card-head">
                        <span class="icon-tile icon-tile--solid"><x-icon :name="$industry['icon']" /></span>
                        <div>
                            <h3><a href="{{ route('industries.show', $industry['slug']) }}">{{ $industry['title'] }}</a></h3>
                            <span class="industry-card-count">{{ $items->count() }} {{ \Illuminate\Support\Str::plural('project', $items->count()) }} delivered</span>
                        </div>
                    </div>
                    <p>{{ $industry['summary'] }}</p>
                    @if ($items->isNotEmpty())
                        <ul class="chips">@foreach ($items->take(4) as $p)<li class="chip">{{ $p['title'] }}</li>@endforeach @if ($items->count() > 4)<li class="chip chip--mint">+{{ $items->count() - 4 }} more</li>@endif</ul>
                    @endif
                    <span class="link-arrow" aria-hidden="true">Explore solutions <x-icon name="arrow-right" /></span>
                </article>
            @endforeach
        </div>
    </div>
</section>

<x-cta />
@endsection
