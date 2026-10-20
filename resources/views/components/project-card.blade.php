@props(['project', 'eager' => false, 'tag' => 'h3'])
<article class="card card--hover project-card">
    <div class="project-media @unless ($project['image']) project-media--placeholder @endunless">
        @if ($project['image'])
            @php($thumb = preg_replace('/\.webp$/', '-640.webp', $project['image']))
            <img src="{{ asset(is_file(public_path($thumb)) ? $thumb : $project['image']) }}"
                 @if (is_file(public_path($thumb))) srcset="{{ asset($thumb) }} 640w, {{ asset($project['image']) }} 1280w" sizes="(min-width: 992px) 420px, (min-width: 576px) 50vw, 100vw" @endif
                 alt="{{ $project['image_alt'] ?? $project['title'] }}" width="640" height="400" @if ($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async">
        @else
            <x-icon name="layout" />
        @endif
    </div>
    <div class="project-body">
        <span class="badge">{{ $project['category'] }}</span>
        @if ($project['draft'])<span class="badge badge--draft">Draft · sample</span>@endif
        <{{ $tag }} class="card-title"><a href="{{ route('portfolio.show', $project['slug']) }}">{{ $project['title'] }}</a></{{ $tag }}>
        <p>{{ $project['summary'] }}</p>
        <span class="link-arrow" aria-hidden="true">View case study <x-icon name="arrow-right" /></span>
    </div>
</article>
