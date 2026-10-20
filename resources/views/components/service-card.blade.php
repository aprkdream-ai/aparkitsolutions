@props(['service', 'tag' => 'h3'])
<article class="card card--hover">
    <span class="icon-tile"><x-icon :name="$service['icon']" /></span>
    <{{ $tag }} class="card-title"><a href="{{ route('services.show', $service['slug']) }}">{{ $service['title'] }}</a></{{ $tag }}>
    <p>{{ $service['summary'] }}</p>
    <span class="link-arrow" aria-hidden="true">Learn more <x-icon name="arrow-right" /></span>
</article>
