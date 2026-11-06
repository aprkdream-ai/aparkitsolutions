@extends('layouts.app')

@section('content')
{{-- Hero --}}
<section class="hero">
    <div class="container hero-grid">
        <div>
            <p class="hero-kicker">Custom software development &amp; business automation</p>
            <h1>We Build Intelligent Software Systems That Power Your Business.</h1>
            <p class="lead">From custom business applications and enterprise platforms to AI-powered automation, we turn complex business requirements into scalable software solutions.</p>
            <div class="btn-row">
                <a class="btn btn--light" href="{{ route('project-brief') }}">Discuss Your Project <x-icon name="arrow-right" class="icon-arrow" /></a>
                <a class="btn btn--ghost-light" href="{{ route('portfolio.index') }}">Explore Our Work</a>
            </div>
        </div>
        <div class="hero-visual">@include('partials.hero-art')</div>
    </div>
</section>

{{-- Company overview: verified figures only, all derived from content files --}}
<section class="trust" aria-label="Company overview">
    <div class="container trust-grid">
        <p class="trust-text">APARK IT Solutions is a custom software engineering and business automation company delivering practical technology solutions for growing businesses and enterprises.</p>
        <dl class="trust-stats">
            <div><dt>Projects delivered</dt><dd>{{ $projectCount }}</dd></div>
            <div><dt>Industries served</dt><dd>{{ $industries->count() }}</dd></div>
            <div><dt>Technologies in our stack</dt><dd>{{ collect($tech)->flatten()->count() }}</dd></div>
            <div><dt>Founded</dt><dd>2024</dd></div>
        </dl>
    </div>
</section>

{{-- Business challenges --}}
<section class="section section--off" id="challenges">
    <div class="container">
        <div class="section-head section-head--row">
            <div>
                <p class="eyebrow">Business challenges we solve</p>
                <h2>Start with the problem, not the technology</h2>
                <p class="lead">Most software projects begin with something in the business that is not working. These are the problems we are asked to solve most often.</p>
            </div>
            <a class="btn btn--outline" href="{{ route('solutions') }}">See how we solve them <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <ul class="challenges">
            @foreach ($solutions as $s)
                <li class="challenge">
                    <span class="icon-tile icon-tile--sm"><x-icon :name="$s['icon']" /></span>
                    <div>
                        <p class="challenge-title"><a href="{{ route('solutions') }}#{{ $s['slug'] }}">{{ $s['title'] }}</a></p>
                        <p>{{ $s['problem'] }}</p>
                        <p class="challenge-links">
                            @foreach ($s['services'] as $service)<a href="{{ route('services.show', $service['slug']) }}">{{ $service['title'] }}</a>@endforeach
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Services --}}
<section class="section" id="services">
    <div class="container">
        <div class="section-head section-head--row">
            <div>
                <p class="eyebrow">Core services</p>
                <h2>Custom software, web &amp; mobile app development services</h2>
                <p class="lead">From focused internal tools to complete enterprise platforms, we design, build, and support software that is reliable and ready to grow.</p>
            </div>
            <a class="btn btn--outline" href="{{ route('services.index') }}">View all services <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="service-groups">
            @foreach ($serviceGroups as $group)
                <div class="service-group">
                    <p class="service-group-title">{{ $group['title'] }}</p>
                    <p class="service-group-text">{{ $group['text'] }}</p>
                    <ul>
                        @foreach ($group['items'] as $service)
                            <li><a href="{{ route('services.show', $service['slug']) }}"><x-icon :name="$service['icon']" /><span>{{ $service['title'] }}</span></a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Industries --}}
@if ($industries->isNotEmpty())
<section class="section section--mint">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">Industry solutions</p>
            <h2>Solutions shaped by industry context</h2>
            <p class="lead">Different sectors face different constraints. We adapt our approach to the realities of yours.</p>
        </div>
        @php($perIndustry = \App\Support\Content::projects()->countBy('industry'))
        <div class="grid grid-industries">
            @foreach ($industries as $industry)
                <x-industry-tile :industry="$industry" :count="$perIndustry[$industry['slug']] ?? 0" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Case studies --}}
@if ($spotlight->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head section-head--row">
            <div>
                <p class="eyebrow">Featured case studies</p>
                <h2>Software development projects we have delivered</h2>
                <p class="lead">A selection from {{ $projectCount }} delivered projects, from AI-powered SaaS to accounting and healthcare platforms.</p>
            </div>
            <a class="btn btn--outline" href="{{ route('portfolio.index') }}">View portfolio <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="cases">
            @foreach ($spotlight as $project)
                <article class="case">
                    <div class="case-media">
                        @if ($project['image'])
                            @php($thumb = preg_replace('/\.webp$/', '-640.webp', $project['image']))
                            <img src="{{ asset($thumb) }}" srcset="{{ asset($thumb) }} 640w, {{ asset($project['image']) }} 1280w" sizes="(min-width: 992px) 620px, 100vw"
                                 alt="{{ $project['image_alt'] }}" width="640" height="400" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="case-body">
                        <span class="badge">{{ $project['category'] }}</span>
                        <h3><a href="{{ route('portfolio.show', $project['slug']) }}">{{ $project['title'] }}</a></h3>
                        <dl class="case-facts">
                            <div><dt>The challenge</dt><dd>{{ $project['challenge'] }}</dd></div>
                            <div><dt>Our solution</dt><dd>{{ $project['solution'] }}</dd></div>
                        </dl>
                        <ul class="chips">@foreach (array_slice($project['tech'], 0, 5) as $t)<li class="chip">{{ $t }}</li>@endforeach</ul>
                        <a class="link-arrow" href="{{ route('portfolio.show', $project['slug']) }}">Read the {{ $project['title'] }} case study <x-icon name="arrow-right" /></a>
                    </div>
                </article>
            @endforeach
        </div>
        @if ($moreWork->isNotEmpty())
            <p class="more-work"><span>More of our work:</span>
                @foreach ($moreWork as $p)<a href="{{ route('portfolio.show', $p['slug']) }}">{{ $p['title'] }}</a>@endforeach
            </p>
        @endif
    </div>
</section>
@endif

{{-- Development approach --}}
<section class="section section--off">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">Development approach</p>
            <h2>Discovery first, code second</h2>
            <p class="lead">Software fails more often from unclear requirements than from bad code. Each project moves through six stages, with your feedback built in.</p>
        </div>
        @include('partials.process')
    </div>
</section>

{{-- Technology --}}
<section class="section">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">Technology capabilities</p>
            <h2>Modern, proven technology stack</h2>
            <p class="lead">We choose tools for reliability, security, and long-term maintainability, matched to each project’s needs.</p>
        </div>
        @include('partials.tech', ['tech' => $tech])
    </div>
</section>

{{-- Why APARK --}}
<section class="section section--navy">
    <div class="container split split--top">
        <div>
            <p class="eyebrow">Why APARK</p>
            <h2>A software partner focused on business outcomes</h2>
            <p class="lead">Writing code is only part of the job. We take time to understand your business, recommend what is genuinely useful, and stay accountable after launch.</p>
            <a class="btn btn--light" href="{{ route('about') }}">About our team <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <ul class="why-list">
            @foreach ([
                ['Business-first development', 'Every recommendation ties back to your goals, budget, and users.'],
                ['Custom-built solutions', 'Software shaped around your workflows, and you own the code.'],
                ['Scalable architecture', 'Designed so new users, features, and integrations do not mean starting over.'],
                ['Transparent process', 'Clear scope, regular demos on staging, and honest timelines.'],
                ['Practical AI adoption', 'AI where it adds real value, with people reviewing what needs judgement.'],
                ['Integration with existing systems', 'We extend what you already have and work alongside your team.'],
                ['Long-term technical support', 'Maintenance, monitoring, and improvements as your business changes.'],
            ] as [$title, $text])
                <li><x-icon name="check-circle" /><div><p class="why-title">{{ $title }}</p><p>{{ $text }}</p></div></li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Testimonials (genuine only) --}}
@if (! empty($testimonials))
<section class="section section--off">
    <div class="container">
        <div class="section-head center">
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
        <div class="section-head section-head--row">
            <div>
                <p class="eyebrow">Insights</p>
                <h2>Practical guidance from our team</h2>
            </div>
            <a class="btn btn--outline" href="{{ route('blog.index') }}">All articles <x-icon name="arrow-right" class="icon-arrow" /></a>
        </div>
        <div class="grid grid-3">
            @foreach ($posts as $post)
                <x-post-card :post="$post" tag="p" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
<section class="section">
    <div class="container split split--top">
        <div>
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
