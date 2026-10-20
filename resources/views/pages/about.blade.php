@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="About us" title="Your technology partner for building, solving, and growing"
    lead="APARK IT Solutions helps businesses turn ideas into dependable digital products. We combine practical business thinking with modern engineering to deliver technology that makes a real difference.">
    <div class="btn-row">
        <a class="btn btn--primary" href="{{ route('contact') }}">Work With Us <x-icon name="arrow-right" class="icon-arrow" /></a>
        <a class="btn btn--outline" href="{{ route('portfolio.index') }}">View our work</a>
    </div>
    <x-slot:aside>@include('partials.hero-stats', ['label' => config('site.slogans.0'), 'chips' => ['Laravel', 'Node.js', 'Python', 'React.js', 'React Native', 'Flutter', 'AWS']])</x-slot:aside>
</x-page-hero>

<section class="section">
    <div class="container split">
        <div class="prose">
            <p class="eyebrow">Who we are</p>
            <h2 class="mt-0">Technology with a clear purpose</h2>
            <p>Founded in 2024, we are a software development and technology consulting company focused on one question: <strong>what will genuinely move your business forward?</strong></p>
            <p>Sometimes the answer is a new website or application. Sometimes it is connecting the systems you already have, automating repetitive work, or simplifying an overcomplicated process. We take time to understand the problem before recommending a solution, and we build with quality, security, and long-term maintainability in mind.</p>
            <p>Our name reflects how we work: <strong>Ideas, Technology, Real Impact</strong>. We start with your ideas, apply the right technology, and measure success by the impact it has on your business.</p>
        </div>
        <div class="grid">
            <div class="panel panel--teal">
                <span class="icon-tile icon-tile--glass"><x-icon name="target" /></span>
                <h3 class="mt-lg">Our mission</h3>
                <p class="mb-0">To help businesses of every size use technology confidently by delivering reliable, well-crafted digital solutions and honest, practical advice.</p>
            </div>
            <div class="panel">
                <span class="icon-tile"><x-icon name="compass" /></span>
                <h3 class="mt-lg">Our vision</h3>
                <p class="mb-0 muted">To be the technology partner businesses trust to build, solve, and grow together, known for quality work and lasting relationships.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section--off">
    <div class="container">
        <div class="section-head center">
            <p class="eyebrow">Our values</p>
            <h2>The principles behind every project</h2>
        </div>
        <div class="grid grid-3">
            @foreach ([
                ['eye', 'Transparency', 'Clear estimates, honest timelines, and open communication, including when things change.'],
                ['shield', 'Quality and security', 'Well-tested, secure, maintainable code. We build software we are proud to hand over.'],
                ['users', 'Partnership', 'We work as an extension of your team and care about your long-term success.'],
                ['bulb', 'Practical innovation', 'We use new technology where it adds real value, not because it is fashionable.'],
                ['target', 'Ownership', 'We take responsibility for outcomes and follow through on our commitments.'],
                ['refresh', 'Continuous improvement', 'We learn from every project and keep refining how we work.'],
            ] as [$icon, $title, $text])
                <div class="card">
                    <span class="icon-tile"><x-icon :name="$icon" /></span>
                    <h3>{{ $title }}</h3>
                    <p>{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container split split--top">
        <div>
            <p class="eyebrow">Our approach</p>
            <h2>How we deliver</h2>
            <p class="lead">A structured, collaborative process keeps every project predictable and focused on business value.</p>
            <ul class="check-list mt-lg">
                <li><x-icon name="check-circle" /><span><strong>Discovery first:</strong> we understand goals, users, and constraints before proposing a solution.</span></li>
                <li><x-icon name="check-circle" /><span><strong>Phased delivery:</strong> early, working releases reduce risk and let you give feedback sooner.</span></li>
                <li><x-icon name="check-circle" /><span><strong>Single point of contact:</strong> clear communication and regular progress updates.</span></li>
                <li><x-icon name="check-circle" /><span><strong>Full handover:</strong> source code, documentation, and access are yours.</span></li>
                <li><x-icon name="check-circle" /><span><strong>Long-term support:</strong> maintenance, monitoring, and continuous improvement.</span></li>
            </ul>
        </div>
        <div>
            <p class="eyebrow">Technical expertise</p>
            <h2>Capabilities across the stack</h2>
            <p class="lead">From websites to multi-tenant platforms, we cover the full lifecycle: design, development, integration, deployment, and support.</p>
            <div class="icon-grid icon-grid--light mt-lg">
                @foreach (\App\Support\Content::services() as $s)
                    <a href="{{ route('services.show', $s['slug']) }}"><x-icon :name="$s['icon']" />{{ \Illuminate\Support\Str::before($s['title'], ' &') }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>

@if (! empty($team))
<section class="section section--off">
    <div class="container">
        <div class="section-head center"><p class="eyebrow">Our team</p><h2>The people behind the work</h2></div>
        <div class="team-grid">
            @foreach ($team as $m)<x-team-card :member="$m" />@endforeach
        </div>
    </div>
</section>
@endif

<section class="section">
    <div class="container">
        <div class="section-head center"><p class="eyebrow">Technology</p><h2>Our technology stack</h2><p class="lead">Proven tools chosen for reliability, security, and long-term maintainability.</p></div>
        @include('partials.tech', ['tech' => $tech])
    </div>
</section>

<x-cta title="Let’s discuss your project" text="Tell us where you are today and where you want to be. We will help you find the right path forward." />
@endsection
