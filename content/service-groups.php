<?php

/*
| How services are grouped on the home page and /services. Hidden or draft services drop out automatically,
| and a service not listed here is added to the last group, so nothing can disappear by accident.
*/

return [
    [
        'title' => 'Build business software',
        'text' => 'Custom systems shaped around how your business runs.',
        'services' => ['custom-software-development', 'erp-crm-solutions', 'saas-product-development', 'web-application-development', 'mobile-app-development'],
    ],
    [
        'title' => 'Connect and automate',
        'text' => 'Join up your systems and take repetitive work out of the day.',
        'services' => ['ai-integration-automation', 'api-development-integration', 'legacy-software-modernization'],
    ],
    [
        'title' => 'Reach your customers',
        'text' => 'Public websites and online stores that bring in business.',
        'services' => ['website-design-development', 'ecommerce-development'],
    ],
    [
        'title' => 'Run and scale',
        'text' => 'Keep software dependable and add capacity when you need it.',
        'services' => ['dedicated-development-teams', 'software-maintenance-support', 'cloud-deployment-consulting'],
    ],
];
