@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Services" title="Software, web, and automation services built around your goals"
    lead="Whether you need a professional website, a custom business platform, or a smarter way to connect your systems, we deliver practical solutions with transparent communication and long-term support.">
    <div class="btn-row">
        <a class="btn btn--primary" href="{{ route('contact') }}">Discuss your project <x-icon name="arrow-right" class="icon-arrow" /></a>
    </div>
</x-page-hero>

<section class="section">
    <div class="container">
        <h2 class="visually-hidden">All services</h2>
        <div class="grid grid-3">
            @foreach ($services as $service)
                <x-service-card :service="$service" />
            @endforeach
        </div>
    </div>
</section>

<section class="section section--off">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow">How we work</p>
            <h2>One process across every service</h2>
            <p class="lead">Discovery, planning, iterative delivery, and careful launch, so you always know what is happening and why.</p>
        </div>
        @include('partials.process')
    </div>
</section>

<x-cta title="Not sure which service you need?" text="Describe the problem you want to solve. We will recommend the most practical approach, even if it is simpler than you expected." />
@endsection
