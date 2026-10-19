<dl class="dl">
    @foreach ($tech as $group => $items)
        <div>
            <dt>{{ $group }}</dt>
            <dd><ul class="chips">@foreach ($items as $item)<li class="chip">{{ $item }}</li>@endforeach</ul></dd>
        </div>
    @endforeach
</dl>
