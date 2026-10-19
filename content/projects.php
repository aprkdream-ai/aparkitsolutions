<?php

/*
| Portfolio / case studies. Keyed by URL slug: /portfolio/{slug}.
| Publish ONLY verified projects (with client permission). The entries below are
| SAMPLE drafts that show the structure. They stay hidden in production and must be
| replaced or deleted before launch.
| Images: put optimised WebP files in public/assets/img/portfolio/ (1600x1000 recommended).
*/

return [

    'sample-operations-portal' => [
        'draft' => true,
        'title' => 'Operations Portal (Sample)',
        'client' => null,                 // client name only with written permission
        'category' => 'Web Application',
        'services' => ['web-application-development', 'api-development-integration'],
        'industry' => null,
        'summary' => 'Sample entry showing how a case study is presented. Replace it with a verified project.',
        'image' => null,                  // e.g. 'assets/img/portfolio/operations-portal.webp'
        'image_alt' => null,
        'url' => null,                    // live project URL, if public
        'tech' => ['Laravel', 'MySQL', 'REST API'],
        'overview' => 'Describe the client, their context, and the goal of the project in two or three sentences.',
        'challenge' => 'Describe the business problem that needed solving.',
        'solution' => 'Describe what was built and the key decisions behind it.',
        'results' => [],                  // verified outcomes only, e.g. ['Order processing moved from spreadsheets to a single portal']
    ],

    'sample-ecommerce-store' => [
        'draft' => true,
        'title' => 'E-commerce Store (Sample)',
        'client' => null,
        'category' => 'E-commerce',
        'services' => ['ecommerce-development', 'website-design-development'],
        'industry' => null,
        'summary' => 'Sample entry showing how a case study is presented. Replace it with a verified project.',
        'image' => null,
        'image_alt' => null,
        'url' => null,
        'tech' => ['PHP', 'Payment Gateway', 'MySQL'],
        'overview' => 'Describe the client, their context, and the goal of the project.',
        'challenge' => 'Describe the business problem that needed solving.',
        'solution' => 'Describe what was built and the key decisions behind it.',
        'results' => [],
    ],

    'sample-mobile-app' => [
        'draft' => true,
        'title' => 'Field Service Mobile App (Sample)',
        'client' => null,
        'category' => 'Mobile App',
        'services' => ['mobile-app-development', 'api-development-integration'],
        'industry' => null,
        'summary' => 'Sample entry showing how a case study is presented. Replace it with a verified project.',
        'image' => null,
        'image_alt' => null,
        'url' => null,
        'tech' => ['Flutter', 'REST API', 'Firebase'],
        'overview' => 'Describe the client, their context, and the goal of the project.',
        'challenge' => 'Describe the business problem that needed solving.',
        'solution' => 'Describe what was built and the key decisions behind it.',
        'results' => [],
    ],
];
