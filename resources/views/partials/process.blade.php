<ol class="timeline">
    @foreach ([
        ['compass', 'Business Discovery', 'Understand business objectives, operational challenges, and requirements.'],
        ['target', 'Requirement Analysis', 'Identify workflows, user roles, integrations, permissions, and technical requirements.'],
        ['layers', 'Architecture & Planning', 'Define system architecture, database design, technology stack, and project milestones.'],
        ['code', 'Design & Development', 'Create responsive interfaces and develop secure, maintainable software.'],
        ['shield', 'Testing & Deployment', 'Perform functional testing, security checks, performance validation, and deployment.'],
        ['refresh', 'Continuous Improvement', 'Provide maintenance, monitoring, optimization, and future enhancements.'],
    ] as $i => [$icon, $title, $text])
        <li>
            <span class="timeline-node"><x-icon :name="$icon" /></span>
            <span class="timeline-step">Step {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <p class="timeline-title">{{ $title }}</p>
            <p>{{ $text }}</p>
        </li>
    @endforeach
</ol>
