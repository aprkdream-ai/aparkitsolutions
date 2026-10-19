@props(['service'])
<article class="card card--hover">
    <span class="icon-tile"><x-icon :name="$service['icon']" /></span>
    <h3><a href="{{ route('services.show', $service['slug']) }}">{{ $service['title'] }}</a></h3>
    <p>{{ $service['summary'] }}</p>
    <span class="link-arrow" aria-hidden="true">Learn more <x-icon name="arrow-right" /></span>
</article>
