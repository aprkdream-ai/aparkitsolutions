@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="is-disabled" aria-disabled="true"><span aria-hidden="true">‹</span><span class="visually-hidden">Previous page</span></li>
            @else
                <li><a href="{{ $paginator->currentPage() === 2 ? $paginator->path() : $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">‹</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="is-disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $page === 1 ? $paginator->path() : $url }}" aria-label="Page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">›</a></li>
            @else
                <li class="is-disabled" aria-disabled="true"><span aria-hidden="true">›</span><span class="visually-hidden">Next page</span></li>
            @endif
        </ul>
    </nav>
@endif
