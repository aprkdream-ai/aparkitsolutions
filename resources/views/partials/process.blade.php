<ol class="process reveal">
    @foreach ([
        ['Discover', 'We learn about your goals, users, and constraints, and define what success looks like.'],
        ['Plan', 'Scope, priorities, architecture, and a realistic timeline with clear milestones.'],
        ['Design', 'User flows, wireframes, and interface design reviewed with you before development.'],
        ['Build', 'Iterative development with regular demos on a staging environment.'],
        ['Test', 'Functional, cross-device, performance, and security checks before release.'],
        ['Launch & support', 'Careful deployment, handover, and ongoing improvements.'],
    ] as [$title, $text])
        <li><h3>{{ $title }}</h3><p>{{ $text }}</p></li>
    @endforeach
</ol>
