<ol class="timeline">
    @foreach ([
        ['compass', 'Discover', 'We learn about your goals, users, and constraints, and define what success looks like.'],
        ['target', 'Plan', 'Scope, priorities, architecture, and a realistic timeline with clear milestones.'],
        ['layout', 'Design', 'User flows, wireframes, and interface design reviewed with you before development.'],
        ['code', 'Build', 'Iterative development with regular demos on a staging environment.'],
        ['shield', 'Test', 'Functional, cross-device, performance, and security checks before release.'],
        ['rocket', 'Launch & support', 'Careful deployment, handover, and ongoing improvements.'],
    ] as $i => [$icon, $title, $text])
        <li>
            <span class="timeline-node"><x-icon :name="$icon" /></span>
            <span class="timeline-step">Step {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <h3>{{ $title }}</h3>
            <p>{{ $text }}</p>
        </li>
    @endforeach
</ol>
