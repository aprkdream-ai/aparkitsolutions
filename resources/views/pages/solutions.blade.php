@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Solutions" title="Business challenges we solve with software"
    lead="Most projects start with something in the business that is not working. Here is how we approach the problems we are asked about most often.">
    <div class="btn-row">
        <a class="btn btn--light" href="{{ route('project-brief') }}">Discuss Your Project <x-icon name="arrow-right" class="icon-arrow" /></a>
        <a class="btn btn--ghost-light" href="{{ route('industries.index') }}">Browse by industry</a>
    </div>
</x-page-hero>

<section class="section">
    <div class="container solutions">
        @foreach ($solutions as $s)
            <article class="solution" id="{{ $s['slug'] }}">
                <div class="solution-head">
                    <span class="icon-tile"><x-icon :name="$s['icon']" /></span>
                    <h2>{{ $s['title'] }}</h2>
                </div>
                <dl class="case-facts">
                    <div><dt>The problem</dt><dd>{{ $s['problem'] }}</dd></div>
                    <div><dt>How we approach it</dt><dd>{{ $s['approach'] }}</dd></div>
                </dl>
                <div class="solution-links">
                    <p class="glass-label">Relevant services</p>
                    <ul class="aside-links">
                        @foreach ($s['services'] as $service)
                            <li><a href="{{ route('services.show', $service['slug']) }}">{{ $service['title'] }} <x-icon name="arrow-right" /></a></li>
                        @endforeach
                    </ul>
                    @if ($s['projects']->isNotEmpty())
                        <p class="glass-label">Related work</p>
                        <ul class="aside-links">
                            @foreach ($s['projects'] as $project)
                                <li><a href="{{ route('portfolio.show', $project['slug']) }}">{{ $project['title'] }} <x-icon name="arrow-right" /></a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section section--off">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">Development approach</p>
            <h2>How we turn a problem into working software</h2>
        </div>
        @include('partials.process')
    </div>
</section>

<x-cta title="Facing a different challenge?" text="Describe what is not working. We will tell you honestly whether software is the right answer, and what we would do first." />
@endsection
