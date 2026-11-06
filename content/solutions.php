<?php

/*
| Business challenges shown on the home page and /solutions. Keyed by anchor: /solutions#{slug}.
| 'services' and 'projects' are slugs; hidden or draft entries drop out automatically.
| Only link a project whose documented features genuinely address the challenge. No outcome claims here.
*/

return [

    'disconnected-applications' => [
        'title' => 'Disconnected business applications',
        'icon' => 'plug',
        'problem' => 'Sales, accounts, and operations each keep their own data, so the same information is typed in twice and nobody fully trusts it.',
        'approach' => 'We map where each record starts and who owns it, then connect your systems through documented APIs or bring the workflows onto one platform, whichever is simpler to run.',
        'services' => ['api-development-integration', 'erp-crm-solutions'],
        'projects' => ['bigul', 'qonsistic'],
    ],

    'manual-processes' => [
        'title' => 'Manual operational processes',
        'icon' => 'layers',
        'problem' => 'Approvals, handovers, and status updates depend on email, spreadsheets, and memory, which slows work down and lets tasks slip.',
        'approach' => 'We document the process as it really runs, remove the steps that add nothing, and build the rest into software with clear roles, permissions, and an audit trail.',
        'services' => ['custom-software-development', 'erp-crm-solutions'],
        'projects' => ['doctorbuddy', 'manuflowcrm'],
    ],

    'inefficient-reporting' => [
        'title' => 'Inefficient reporting',
        'icon' => 'trending',
        'problem' => 'Reports are assembled by hand from several exports, so they arrive late and the numbers change depending on who prepared them.',
        'approach' => 'We agree the figures that matter and their definitions, then generate reports directly from the system of record so everyone reads the same numbers.',
        'services' => ['custom-software-development', 'web-application-development'],
        'projects' => ['reco'],
    ],

    'legacy-limitations' => [
        'title' => 'Legacy software limitations',
        'icon' => 'refresh',
        'problem' => 'An older system still runs the business, but it is hard to change, hard to integrate, and depends on knowledge few people have.',
        'approach' => 'We start with a code and architecture review, then recommend whether to stabilise, extend, or replace it in stages, so the business keeps running during the change.',
        'services' => ['legacy-software-modernization', 'custom-software-development', 'software-maintenance-support'],
        'projects' => [],
    ],

    'limited-visibility' => [
        'title' => 'Lack of business visibility',
        'icon' => 'eye',
        'problem' => 'Owners and managers cannot see the current state of customers, projects, or money without asking someone to check.',
        'approach' => 'We bring operational data into role-based dashboards and admin panels, so each team sees what it needs, when it needs it.',
        'services' => ['erp-crm-solutions', 'web-application-development'],
        'projects' => ['aero-erp', 'bigul'],
    ],

    'repetitive-admin' => [
        'title' => 'Repetitive administrative work',
        'icon' => 'zap',
        'problem' => 'Skilled people spend their day on follow-ups, data entry, and document handling that follows the same pattern every time.',
        'approach' => 'We automate the predictable steps with workflows, and add AI only where it is genuinely useful, with a person reviewing anything that needs judgement.',
        'services' => ['ai-integration-automation', 'custom-software-development'],
        'projects' => ['qonsistic', 'riffora'],
    ],

    'difficult-integrations' => [
        'title' => 'Difficult third-party integrations',
        'icon' => 'code',
        'problem' => 'Payment gateways, KYC providers, and partner APIs behave differently, fail in unexpected ways, and are risky to change once live.',
        'approach' => 'We build integrations with authentication, retries, logging, and clear failure handling, so a problem with a provider does not become a problem for your customers.',
        'services' => ['api-development-integration', 'web-application-development'],
        'projects' => ['bigul', 'riffora'],
    ],

];
