@extends('layouts.app')

@section('content')
{{-- Hero --}}
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="pill"><span class="pill-dot" aria-hidden="true"></span>{{ config('site.slogans.0') }}</p>
            <h1>Ideas Into Technology. <span class="text-gradient">Technology Into Real Impact.</span></h1>
            <p class="lead">We help businesses transform ideas into scalable digital solutions through custom software development, modern web applications, intelligent automation, and technology consulting.</p>
            <div class="btn-row">
                <a class="btn btn--primary" href="{{ route('contact') }}">Start a Project <x-icon name="arrow-right" class="icon-arrow" /></a>
                <a class="btn btn--outline" href="{{ route('services.index') }}">Explore Our Services</a>
            </div>
            <ul class="hero-points">
                <li><x-icon name="check-circle" /> Tailored to your business</li>
                <li><x-icon name="check-circle" /> Transparent delivery</li>
                <li><x-icon name="check-circle" /> You own the code</li>
            </ul>
        </div>
        <div class="hero-visual" aria-hidden="false">
            @include('partials.hero-art')
            <div class="float-card float-card--1" aria-hidden="true"><span class="ic"><x-icon name="code" /></span><span>Build<small>Software &amp; apps</small></span></div>
            <div class="float-card float-card--2" aria-hidden="true"><span class="ic"><x-icon name="zap" /></span><span>Solve<small>Integration &amp; automation</small></span></div>
            <div class="float-card float-card--3" aria-hidden="true"><span class="ic"><x-icon name="trending" /></span><span>Grow Together<small>Scalable platforms</small></span></div>
        </div>
    </div>
</section>

{{-- Capability highlights --}}
<section class="highlights" aria-labelledby="highlights-title">
    <div class="container">
        <h2 class="visually-hidden" id="highlights-title">What we bring to every project</h2>
        <div class="highlights-grid">
            @foreach ([
                ['bulb', 'Tailored solutions', 'Built around your goals, users, and processes.'],
                ['layers', 'Modern technology', 'Proven, well-supported frameworks and tools.'],
                ['shield', 'Security by design', 'Secure practices from the first line of code.'],
                ['eye', 'Transparent delivery', 'Clear scope, regular demos, honest timelines.'],
            ] as [$icon, $title, $text])
                <div class="highlight">
                    <span class="icon-tile"><x-icon :name="$icon" /></span>
                    <div><h3>{{ $title }}</h3><p>{{ $text }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Services --}}
<section class="section" id="services">
    <div class="container">
        <div class="section-head section-head--row reveal">
            <div>
                <p class="eyebrow">What we do</p>
                <h2>Technology services that solve real business problems</h2>
                <p class="lead">From your first website to complex business platforms, we design, build, and support digital products that are reliable and ready to grow.</p>
            </div>
            <a class="btn btn--outline" href="{{ route('services.index') }}">View all services <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="grid grid-3">
            @foreach ($services as $service)
                <x-service-card :service="$service" />
            @endforeach
        </div>
    </div>
</section>

{{-- Why choose --}}
<section class="section section--off">
    <div class="container split split--top">
        <div class="reveal">
            <p class="eyebrow">Why APARK IT Solutions</p>
            <h2>A technology partner focused on outcomes, not just output</h2>
            <p class="lead">Writing code is only part of the job. We take time to understand your business, recommend what is genuinely useful, and stay accountable for quality long after launch.</p>
            <div class="btn-row mt-lg">
                <a class="btn btn--primary" href="{{ route('about') }}">About our approach <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
        </div>
        <div class="grid grid-features reveal">
            @foreach ([
                ['target', 'Business-first thinking', 'Every recommendation ties back to your goals, budget, and users.'],
                ['layers', 'End-to-end delivery', 'Strategy, design, development, deployment, and support under one roof.'],
                ['message', 'Clear communication', 'A single point of contact, regular updates, and no jargon.'],
                ['shield', 'Quality and security', 'Code reviews, testing, and secure defaults on every project.'],
                ['trending', 'Built to scale', 'Architecture that supports new users, features, and integrations.'],
                ['users', 'Long-term partnership', 'Ongoing maintenance and improvement as your business evolves.'],
            ] as [$icon, $title, $text])
                <div class="feature">
                    <span class="icon-tile"><x-icon :name="$icon" /></span>
                    <div><h3>{{ $title }}</h3><p>{{ $text }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Portfolio --}}
@if ($projects->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head section-head--row reveal">
            <div>
                <p class="eyebrow">Featured work</p>
                <h2>Selected projects</h2>
                <p class="lead">A look at the problems we have solved and the solutions we delivered.</p>
            </div>
            <a class="btn btn--outline" href="{{ route('portfolio.index') }}">View portfolio <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="grid grid-3">
            @foreach ($projects as $project)
                <x-project-card :project="$project" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Industries --}}
@if ($industries->isNotEmpty())
<section class="section {{ $projects->isNotEmpty() ? 'section--mint' : '' }}">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow">Industries</p>
            <h2>Solutions shaped by industry context</h2>
            <p class="lead">Different sectors face different constraints. We adapt our approach to the realities of yours.</p>
        </div>
        <div class="grid grid-3">
            @foreach ($industries as $industry)
                <article class="card card--hover">
                    <span class="icon-tile"><x-icon :name="$industry['icon']" /></span>
                    <h3><a href="{{ route('industries.show', $industry['slug']) }}">{{ $industry['title'] }}</a></h3>
                    <p>{{ $industry['summary'] }}</p>
                    @if ($industry['draft'])<span class="badge badge--draft">Draft · not published</span>@endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Process --}}
<section class="section {{ $industries->isNotEmpty() && $projects->isEmpty() ? '' : 'section--off' }}">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow">How we work</p>
            <h2>A clear, collaborative development process</h2>
            <p class="lead">A structured process keeps projects predictable, with your feedback built in at every stage.</p>
        </div>
        @include('partials.process')
    </div>
</section>

{{-- Technology --}}
<section class="section">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow">Technology</p>
            <h2>Modern, proven technology stack</h2>
            <p class="lead">We choose tools for reliability, security, and long-term maintainability, matched to each project’s needs.</p>
        </div>
        @include('partials.tech', ['tech' => $tech])
    </div>
</section>

{{-- Testimonials (genuine only) --}}
@if (! empty($testimonials))
<section class="section section--off">
    <div class="container">
        <div class="section-head center reveal">
            <p class="eyebrow">Client feedback</p>
            <h2>What our clients say</h2>
        </div>
        <div class="grid grid-3">
            @foreach ($testimonials as $t)
                <figure class="card quote">
                    <blockquote>“{{ $t['quote'] }}”</blockquote>
                    <figcaption><strong>{{ $t['name'] }}</strong>{{ collect([$t['role'] ?? null, $t['company'] ?? null])->filter()->implode(', ') }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Insights --}}
@if ($posts->isNotEmpty())
<section class="section {{ empty($testimonials) ? 'section--off' : '' }}">
    <div class="container">
        <div class="section-head section-head--row reveal">
            <div>
                <p class="eyebrow">Insights</p>
                <h2>Practical guidance from our team</h2>
            </div>
            <a class="btn btn--outline" href="{{ route('blog.index') }}">All articles <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="grid grid-3">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
<section class="section">
    <div class="container split split--top">
        <div class="reveal">
            <p class="eyebrow">FAQ</p>
            <h2>Frequently asked questions</h2>
            <p class="lead">Answers to common questions about working with us. Can’t find what you are looking for?</p>
            <a class="link-arrow" href="{{ route('contact') }}">Ask us directly <x-icon name="arrow-right" /></a>
        </div>
        <x-faq :items="$faqs" />
    </div>
</section>

<x-cta />
@endsection
