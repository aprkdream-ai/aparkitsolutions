<title>{{ $seo->fullTitle() }}</title>
<meta name="description" content="{{ $seo->description }}">
<meta name="robots" content="{{ $seo->robots() }}">
<link rel="canonical" href="{{ $seo->canonical }}">
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:type" content="{{ $seo->type }}">
<meta property="og:title" content="{{ $seo->title }}">
<meta property="og:description" content="{{ $seo->description }}">
<meta property="og:url" content="{{ $seo->canonical }}">
<meta property="og:image" content="{{ $seo->imageUrl() }}">
<meta property="og:image:alt" content="{{ $seo->title }}">
<meta property="og:locale" content="en_US">
@if ($seo->type === 'article' && $seo->article)
<meta property="article:published_time" content="{{ $seo->article['published_time'] }}">
<meta property="article:modified_time" content="{{ $seo->article['modified_time'] }}">
<meta property="article:section" content="{{ $seo->article['section'] }}">
@foreach ($seo->article['tags'] as $tag)
<meta property="article:tag" content="{{ $tag }}">
@endforeach
@endif
<meta name="twitter:card" content="summary_large_image">
@if ($handle = config('site.seo.twitter_handle'))
<meta name="twitter:site" content="{{ $handle }}">
@endif
@if ($v = config('site.seo.google_verification'))
<meta name="google-site-verification" content="{{ $v }}">
@endif
@if ($v = config('site.seo.bing_verification'))
<meta name="msvalidate.01" content="{{ $v }}">
@endif
@if ($seo->schema)
<script type="application/ld+json">{!! $seo->jsonLd() !!}</script>
@endif
