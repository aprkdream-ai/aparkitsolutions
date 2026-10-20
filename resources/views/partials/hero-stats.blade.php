@php($projects = \App\Support\Content::projects())
<div class="glass">
    <p class="glass-label">{{ $label ?? 'At a glance' }}</p>
    <div class="hero-stats">
        <div class="hero-stat"><strong>{{ $projects->count() }}</strong><span>Projects delivered</span></div>
        <div class="hero-stat"><strong>{{ \App\Support\Content::industries()->count() }}</strong><span>Industries</span></div>
        <div class="hero-stat"><strong>{{ \App\Support\Content::services()->count() }}</strong><span>Core services</span></div>
    </div>
    @isset($chips)
        <ul class="chips mt-lg">@foreach ($chips as $c)<li class="chip">{{ $c }}</li>@endforeach</ul>
    @endisset
</div>
