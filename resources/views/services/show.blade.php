@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Service" :title="$service['title']" :lead="$service['summary']">
    <div class="btn-row">
        <a class="btn btn--primary" href="{{ route('contact', ['service' => $service['slug']]) }}">Get a Project Quote <x-icon name="arrow-right" class="icon-arrow" /></a>
        <a class="btn btn--outline" href="#approach">Our approach</a>
    </div>
    <x-slot:aside>
        <div class="glass">
            <div class="hero-feature">
                <span class="icon-tile icon-tile--glass"><x-icon :name="$service['icon']" /></span>
                <div><strong>{{ $service['title'] }}</strong><span>{{ count($service['deliverables']) }} deliverables · {{ count($service['approach']) }}-step approach</span></div>
            </div>
            @if (! empty($service['tech']))
                <p class="glass-label">Technologies</p>
                <ul class="chips">@foreach ($service['tech'] as $t)<li class="chip">{{ $t }}</li>@endforeach</ul>
            @endif
        </div>
    </x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container detail">
        <div>
            <div class="detail-section prose">
                @foreach ($service['intro'] as $p)<p>{{ $p }}</p>@endforeach
            </div>

            <div class="detail-section">
                <h2>Benefits</h2>
                <div class="grid grid-benefits">
                    @foreach ($service['benefits'] as $b)
                        <div class="benefit">
                            <span class="benefit-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $b['title'] }}</h3>
                            <p>{{ $b['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="detail-section">
                <h2>What’s included</h2>
                <ul class="check-list check-list--2">
                    @foreach ($service['deliverables'] as $d)<li><x-icon name="check-circle" /><span>{{ $d }}</span></li>@endforeach
                </ul>
            </div>

            @if (! empty($service['tech']))
                <div class="detail-section">
                    <h2>Technologies we use</h2>
                    <ul class="chips">@foreach ($service['tech'] as $t)<li class="chip chip--mint">{{ $t }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="detail-section" id="approach">
                <h2>Our approach</h2>
                <ol class="process">
                    @foreach ($service['approach'] as $step)<li><h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p></li>@endforeach
                </ol>
            </div>

            <div class="detail-section">
                <h2>Frequently asked questions</h2>
                <x-faq :items="$service['faqs']" />
            </div>
        </div>

        <aside>
            <div class="aside-card aside-card--teal">
                <h2>Planning a {{ \Illuminate\Support\Str::lower($service['title']) }} project?</h2>
                <p>Share your requirements and we will get back to you with next steps.</p>
                <a class="btn btn--light btn--block" href="{{ route('contact', ['service' => $service['slug']]) }}">Get in touch <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
            @if ($related->isNotEmpty())
                <div class="aside-card">
                    <h2>Related services</h2>
                    <ul class="aside-links">
                        @foreach ($related as $r)
                            <li><a href="{{ route('services.show', $r['slug']) }}">{{ $r['title'] }} <x-icon name="arrow-right" /></a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</section>

@if ($projects->isNotEmpty())
<section class="section section--off">
    <div class="container">
        <div class="section-head"><p class="eyebrow">Related work</p><h2>Projects using this service</h2></div>
        <div class="grid grid-3">
            @foreach ($projects as $project)<x-project-card :project="$project" />@endforeach
        </div>
    </div>
</section>
@endif

<x-cta :service="$service['slug']" />
@endsection
