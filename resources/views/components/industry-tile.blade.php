@props(['industry', 'count' => 0])
<a class="industry-tile" href="{{ route('industries.show', $industry['slug']) }}">
    <span class="icon-tile"><x-icon :name="$industry['icon']" /></span>
    <span class="industry-tile-title">{{ $industry['title'] }}</span>
    <span class="industry-tile-meta">{{ $count }} {{ \Illuminate\Support\Str::plural('project', $count) }} <x-icon name="arrow-right" /></span>
</a>
