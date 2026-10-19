<?php

/*
| Industries served. Keyed by URL slug: /industries/{slug}.
| Remove 'draft' => true ONLY for industries APARK actually serves. Drafts are hidden in production;
| when nothing is published, the Industries menu item and home section disappear.
*/

return [

    'retail-ecommerce' => [
        'draft' => true,
        'title' => 'Retail & E-commerce',
        'icon' => 'cart',
        'summary' => 'Online stores, inventory and order systems, and integrations that connect sales channels.',
        'intro' => 'Retailers need fast storefronts, accurate inventory, and smooth fulfilment across channels. We help connect the systems behind the sale so teams spend less time reconciling orders and stock.',
        'challenges' => ['Inventory out of sync across channels', 'Manual order processing', 'Slow or hard-to-use storefronts'],
        'solutions' => ['ecommerce-development', 'api-development-integration', 'erp-crm-solutions'],
    ],

    'healthcare' => [
        'draft' => true,
        'title' => 'Healthcare & Clinics',
        'icon' => 'heart',
        'summary' => 'Appointment booking, patient communication, and secure internal tools for clinics and providers.',
        'intro' => 'Healthcare teams need dependable tools that respect patient privacy. We build booking systems, portals, and internal applications with careful attention to access control and data handling.',
        'challenges' => ['Manual appointment scheduling', 'Paper-based or scattered records', 'Strict privacy expectations'],
        'solutions' => ['web-application-development', 'mobile-app-development', 'custom-software-development'],
    ],

    'education' => [
        'draft' => true,
        'title' => 'Education & Training',
        'icon' => 'graduation',
        'summary' => 'Learning portals, student management, and course platforms for institutes and trainers.',
        'intro' => 'Education providers increasingly deliver and manage learning online. We build portals and management systems that make enrolment, content delivery, and communication simpler.',
        'challenges' => ['Disconnected enrolment and fee tracking', 'Limited online learning tools', 'Manual communication with students'],
        'solutions' => ['web-application-development', 'erp-crm-solutions', 'mobile-app-development'],
    ],

    'logistics' => [
        'draft' => true,
        'title' => 'Logistics & Distribution',
        'icon' => 'truck',
        'summary' => 'Tracking, dispatch, and field applications that give teams real-time visibility.',
        'intro' => 'Logistics depends on timely information. We build tracking dashboards, dispatch tools, and driver apps that keep operations, drivers, and customers informed.',
        'challenges' => ['Limited shipment visibility', 'Phone- and spreadsheet-based dispatch', 'Disconnected partner systems'],
        'solutions' => ['mobile-app-development', 'api-development-integration', 'custom-software-development'],
    ],

    'professional-services' => [
        'draft' => true,
        'title' => 'Professional Services',
        'icon' => 'briefcase',
        'summary' => 'Client portals, CRM, and workflow automation for agencies, consultancies, and firms.',
        'intro' => 'Service firms run on client relationships and efficient delivery. We build CRMs, client portals, and automated workflows that reduce admin and improve client experience.',
        'challenges' => ['Client information spread across tools', 'Repetitive document work', 'Manual reporting'],
        'solutions' => ['erp-crm-solutions', 'ai-integration-automation', 'website-design-development'],
    ],

    'startups' => [
        'draft' => true,
        'title' => 'Startups & SaaS',
        'icon' => 'rocket',
        'summary' => 'MVPs and SaaS products built to validate ideas quickly and scale responsibly.',
        'intro' => 'Startups need to learn fast without building on shaky foundations. We help define a focused MVP, build it on a scalable architecture, and iterate with real user feedback.',
        'challenges' => ['Limited time and budget', 'Uncertain product scope', 'Need for a scalable foundation'],
        'solutions' => ['web-application-development', 'mobile-app-development', 'cloud-deployment-consulting'],
    ],
];
