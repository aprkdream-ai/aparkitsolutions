<div class="grid grid-3 reveal">
    @foreach ($tech as $group => $items)
        <div class="tech-group">
            <h3>{{ $group }}</h3>
            <ul class="chips">
                @foreach ($items as $item)<li class="chip">{{ $item }}</li>@endforeach
            </ul>
        </div>
    @endforeach
</div>
