@props(['project', 'headingLevel' => 3])
<article class="card card--hover project-card">
    <div class="project-media @unless ($project['image']) project-media--placeholder @endunless">
        @if ($project['image'])
            <img src="{{ asset($project['image']) }}" alt="{{ $project['image_alt'] ?? $project['title'] }}" width="800" height="500" loading="lazy" decoding="async">
        @else
            <x-icon name="layout" />
        @endif
    </div>
    <div class="project-body">
        <span class="badge">{{ $project['category'] }}</span>
        @if ($project['draft'])<span class="badge badge--draft">Draft · sample</span>@endif
        <h3><a href="{{ route('portfolio.show', $project['slug']) }}">{{ $project['title'] }}</a></h3>
        <p>{{ $project['summary'] }}</p>
        <span class="link-arrow" aria-hidden="true">View case study <x-icon name="arrow-right" /></span>
    </div>
</article>
