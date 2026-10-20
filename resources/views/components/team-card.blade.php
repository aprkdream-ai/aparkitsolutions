@props(['member'])
@php($hasPhoto = ! empty($member['photo']) && is_file(public_path($member['photo'])))
@php($initials = collect(explode(' ', $member['name']))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode(''))
<article class="team-card">
    <div class="team-photo">
        @if ($hasPhoto)
            <img class="team-img" src="{{ asset($member['photo']) }}" alt="{{ $member['name'] }}" width="600" height="600" loading="lazy" decoding="async">
        @else
            <span class="team-initials" aria-hidden="true">{{ $initials }}</span>
        @endif
    </div>
    <div class="team-body">
        <h3>{{ $member['name'] }}</h3>
        <p class="team-role">{{ $member['role'] }}</p>
    </div>
</article>
