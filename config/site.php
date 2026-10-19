<?php

/*
| Central company information and site settings.
| Leave a value empty to hide the related UI (e.g. no phone => no phone link).
| Never put unverified facts here.
*/

$csv = fn (?string $v) => array_values(array_filter(array_map('trim', explode(',', (string) $v))));

return [

    'name' => env('SITE_NAME', 'APARK IT Solutions'),
    'short_name' => 'APARK',
    'tagline' => 'Your Technology Partner',
    'slogans' => ['Ideas | Technology | Real Impact', 'Build | Solve | Grow Together'],
    'legal_name' => env('SITE_LEGAL_NAME', 'APARK IT Solutions'),

    // Set to a file in public/ (e.g. "assets/img/brand/logo.svg") to use a supplied logo instead of the built-in mark.
    'logo' => env('SITE_LOGO'),

    // Search engine indexing. Defaults to true only in production; staging is always noindex.
    'indexable' => (bool) env('SITE_INDEXABLE', env('APP_ENV') === 'production'),

    // Redirect to the scheme + host of APP_URL (HTTPS and canonical hostname). Enable after SSL is active.
    'force_canonical' => (bool) env('FORCE_CANONICAL_URL', false),

    // Comma-separated proxy IPs, or "*", only when behind a known proxy/CDN (e.g. Cloudflare).
    'trusted_proxies' => env('TRUSTED_PROXIES'),

    // Show content marked 'draft' => true (sample entries). Use for local review only.
    'show_drafts' => (bool) env('CONTENT_SHOW_DRAFTS', false),

    // Set true once the business has reviewed the legal pages (removes the notice and allows indexing).
    'legal_reviewed' => (bool) env('LEGAL_REVIEWED', false),
    'legal_updated' => env('LEGAL_UPDATED', '2026-10-19'),

    'contact' => [
        'email' => env('CONTACT_EMAIL'),
        'phone' => env('CONTACT_PHONE'),             // display format, e.g. "+1 555 010 0000"
        'whatsapp' => env('CONTACT_WHATSAPP'),       // digits with country code, e.g. 15550100000
        'address' => env('CONTACT_ADDRESS'),         // single line, verified address only
        'map_embed' => env('CONTACT_MAP_EMBED_URL'), // Google Maps "embed" src URL
        'hours' => env('CONTACT_HOURS'),
        'recipient' => env('CONTACT_RECIPIENT', env('CONTACT_EMAIL')),
        'store_submissions' => (bool) env('CONTACT_STORE_SUBMISSIONS', true),
        'budgets' => [
            'Not decided yet',
            'Small project or MVP',
            'Medium project',
            'Large or ongoing engagement',
        ],
    ],

    'social' => array_filter([
        'LinkedIn' => env('SOCIAL_LINKEDIN'),
        'GitHub' => env('SOCIAL_GITHUB'),
        'X' => env('SOCIAL_X'),
        'Facebook' => env('SOCIAL_FACEBOOK'),
        'Instagram' => env('SOCIAL_INSTAGRAM'),
        'YouTube' => env('SOCIAL_YOUTUBE'),
    ]),

    'seo' => [
        'title_separator' => ' | ',
        'default_title' => 'APARK IT Solutions — Custom Software, Web & App Development',
        'default_description' => 'APARK IT Solutions builds custom software, websites, web and mobile applications, integrations, and automation that help businesses grow. Talk to us about your project.',
        'default_image' => 'assets/img/og-default.png',
        'twitter_handle' => env('SEO_TWITTER_HANDLE'),
        'google_verification' => env('SEO_GOOGLE_VERIFICATION'),
        'bing_verification' => env('SEO_BING_VERIFICATION'),
        // Areas served (for Organization schema). Leave empty unless confirmed.
        'area_served' => $csv(env('SEO_AREA_SERVED')),
    ],

    'blog' => [
        'per_page' => (int) env('BLOG_PER_PAGE', 9),
        'authors' => [
            'apark-team' => [
                'name' => 'APARK IT Solutions Team',
                'bio' => 'Engineers and consultants at APARK IT Solutions writing about software delivery, web technology, and practical automation.',
            ],
        ],
    ],

    'nav' => [
        ['label' => 'Services', 'route' => 'services.index'],
        ['label' => 'Portfolio', 'route' => 'portfolio.index', 'requires' => 'projects'],
        ['label' => 'Industries', 'route' => 'industries.index', 'requires' => 'industries'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Insights', 'route' => 'blog.index'],
    ],
];
