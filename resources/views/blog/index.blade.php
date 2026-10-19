@extends('layouts.app')

@section('content')
<x-page-hero :seo="$seo" eyebrow="Insights" :title="$activeCategory ? $categories[$activeCategory] : 'Insights & articles'"
    lead="Practical articles on software development, web technology, integration, and automation, written to help you make better technology decisions." />

<section class="section">
    <div class="container">
        @if ($categories->count() > 1)
            <nav class="filter-bar" aria-label="Article categories">
                <a class="chip {{ $activeCategory ? '' : 'is-active' }}" href="{{ route('blog.index') }}" @unless ($activeCategory) aria-current="page" @endunless>All</a>
                @foreach ($categories as $slug => $label)
                    <a class="chip {{ $activeCategory === $slug ? 'is-active' : '' }}" href="{{ route('blog.category', $slug) }}" @if ($activeCategory === $slug) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
            </nav>
        @endif

        @if ($posts->isEmpty())
            <div class="empty-state">
                <span class="icon-tile"><x-icon name="bulb" /></span>
                <h2>No articles yet</h2>
                <p>New articles are on the way. Check back soon.</p>
            </div>
        @else
            <h2 class="visually-hidden">Articles</h2>
            <div class="grid grid-3">
                @foreach ($posts as $post)<x-post-card :post="$post" />@endforeach
            </div>
            {{ $posts->links() }}
        @endif
    </div>
</section>

<x-cta />
@endsection
