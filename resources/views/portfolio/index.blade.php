@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Portfolio" title="Our work and case studies"
    lead="Real projects, explained honestly: the challenge, the solution we delivered, and the technology behind it." />

<section class="section">
    <div class="container">
        @if ($projects->isEmpty())
            <div class="empty-state">
                <span class="icon-tile"><x-icon name="folder" /></span>
                <h2>Case studies are on the way</h2>
                <p>We are preparing detailed case studies of recent work. In the meantime, we are happy to walk you through relevant examples on a call.</p>
                <a class="btn btn--primary" href="{{ route('contact') }}">Request examples <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
        @else
            @if ($projects->contains('draft', true))
                <p class="notice notice--draft"><x-icon name="alert" /> Draft sample entries are visible because CONTENT_SHOW_DRAFTS is enabled. They are hidden in production.</p>
            @endif
            @if ($categories->count() > 1)
                <nav class="filter-bar" data-filter-bar aria-label="Filter projects by category">
                    <a class="chip {{ $active ? '' : 'is-active' }}" href="{{ route('portfolio.index') }}" data-filter="" @unless ($active) aria-current="true" @endunless>All</a>
                    @foreach ($categories as $slug => $label)
                        <a class="chip {{ $active === $slug ? 'is-active' : '' }}" href="{{ route('portfolio.index', ['category' => $slug]) }}" data-filter="{{ $slug }}" @if ($active === $slug) aria-current="true" @endif>{{ $label }}</a>
                    @endforeach
                </nav>
            @endif
            <h2 class="visually-hidden">Projects</h2>
            <div class="grid grid-3">
                @foreach ($projects as $project)
                    <div data-category="{{ \Illuminate\Support\Str::slug($project['category']) }}" class="{{ $active && \Illuminate\Support\Str::slug($project['category']) !== $active ? 'is-hidden' : '' }}">
                        <x-project-card :project="$project" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<x-cta title="Have a project like these in mind?" />
@endsection
