@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Services" title="Software engineering and automation services built around your goals"
    lead="Whether you need a professional website, a custom business platform, or a smarter way to connect your systems, we deliver practical solutions with transparent communication and long-term support.">
    <div class="btn-row">
        <a class="btn btn--primary" href="{{ route('project-brief') }}">Get a Quote <x-icon name="arrow-right" class="icon-arrow" /></a>
        <a class="btn btn--outline" href="{{ route('portfolio.index') }}">See our work</a>
    </div>
    <x-slot:aside>
        <div class="glass">
            <p class="glass-label">{{ $services->count() }} core services</p>
            <ul class="icon-grid icon-grid--static">
                @foreach ($services as $s)
                    <li><x-icon :name="$s['icon']" />{{ \Illuminate\Support\Str::before($s['title'], ' &') }}</li>
                @endforeach
            </ul>
        </div>
    </x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container service-sections">
        @foreach ($groups as $group)
            <div>
                <div class="section-head">
                    <h2>{{ $group['title'] }}</h2>
                    <p class="lead">{{ $group['text'] }}</p>
                </div>
                <div class="grid grid-3">
                    @foreach ($group['items'] as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="section section--off">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">How we work</p>
            <h2>One process across every service</h2>
            <p class="lead">Discovery, planning, iterative delivery, and careful launch, so you always know what is happening and why.</p>
        </div>
        @include('partials.process')
    </div>
</section>

<x-cta title="Not sure which service you need?" text="Describe the problem you want to solve. We will recommend the most practical approach, even if it is simpler than you expected." />
@endsection
