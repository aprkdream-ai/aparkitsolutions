@if (! empty($seo->breadcrumbs))
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol>
            @foreach ($seo->breadcrumbs as $label => $url)
                <li>
                    @if ($url && ! $loop->last)
                        <a href="{{ $url }}">{{ $label }}</a>
                    @else
                        <span aria-current="page">{{ $label }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
