@props(['post', 'tag' => 'h3'])
<article class="card card--hover post-card">
    <span class="badge">{{ $post['category'] }}</span>
    <{{ $tag }} class="card-title"><a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></{{ $tag }}>
    <p>{{ $post['description'] }}</p>
    <div class="post-meta">
        <span><x-icon name="calendar" /><time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('M j, Y') }}</time></span>
        <span><x-icon name="clock" />{{ $post['reading_time'] }} min read</span>
    </div>
</article>
