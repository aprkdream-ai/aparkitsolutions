@props(['items'])
<div class="faq">
    @foreach ($items as $item)
        <details @if ($loop->first) open @endif>
            <summary>{{ $item['q'] }} <x-icon name="chevron-down" /></summary>
            <div class="faq-a"><p>{{ $item['a'] }}</p></div>
        </details>
    @endforeach
</div>
