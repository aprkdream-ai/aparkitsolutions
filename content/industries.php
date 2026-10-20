<?php

/*
| Industries served, based on delivered projects (content/projects.php 'industry' field).
| Keyed by URL slug: /industries/{slug}. Each industry page lists its related projects automatically.
*/

return [

    'healthcare' => [
        'title' => 'Healthcare',
        'icon' => 'heart',
        'summary' => 'Hospital data systems, doctor appointment booking, and content-managed healthcare websites.',
        'intro' => 'Healthcare organisations need dependable software that keeps information organised and accessible. We have built hospital data management systems with mobile app APIs, a doctor–medical representative appointment platform, and dynamic informational websites for healthcare programmes.',
        'challenges' => ['Scattered patient and operational data', 'Manual appointment coordination', 'Content that changes frequently and needs to be updated without developers'],
        'solutions' => ['custom-software-development', 'web-application-development', 'api-development-integration'],
    ],

    'fintech' => [
        'title' => 'Finance, Fintech & Insurance',
        'icon' => 'trending',
        'summary' => 'Trading platforms with eKYC, loan management, accounting SaaS with GST, and insurance CRM.',
        'intro' => 'Financial products demand accuracy, security, and reliable integrations. Our work includes a trading platform with an eKYC journey and NSE/BSE and payment gateway integrations, a beneficiary loan management system, an accounting SaaS with GST reports and Razorpay, a KYC and document verification platform, and an insurance CRM.',
        'challenges' => ['Regulated onboarding such as eKYC', 'Integrations with exchanges and payment gateways', 'Accurate accounting and tax reporting'],
        'solutions' => ['web-application-development', 'api-development-integration', 'erp-crm-solutions'],
    ],

    'education' => [
        'title' => 'Education',
        'icon' => 'graduation',
        'summary' => 'College and school management systems with integrated websites, admissions, and administration.',
        'intro' => 'Schools and colleges run on admissions, academics, notices, and administration. We have delivered college and school management systems that combine a public website with student and administrative tools.',
        'challenges' => ['Manual admissions and record keeping', 'Communicating notices and updates', 'Disconnected websites and back-office tools'],
        'solutions' => ['erp-crm-solutions', 'website-design-development', 'web-application-development'],
    ],

    'real-estate' => [
        'title' => 'Real Estate & Interiors',
        'icon' => 'building',
        'summary' => 'Property listing and builder websites, project showcases, and interior design studio sites with enquiry capture.',
        'intro' => 'Real estate buyers research online before they call. We build property and builder websites with searchable listings, project progress, and enquiry and WhatsApp lead capture, as well as interior design websites, all managed through admin panels.',
        'challenges' => ['Presenting many projects and listings clearly', 'Capturing and routing enquiries', 'Keeping listings up to date'],
        'solutions' => ['website-design-development', 'web-application-development'],
    ],

    'retail-ecommerce' => [
        'title' => 'Retail, E-commerce & Local Business',
        'icon' => 'cart',
        'summary' => 'Online stores with payment gateways, grocery delivery, and websites for local service businesses.',
        'intro' => 'Selling online requires a smooth storefront and reliable payments. We have built multi-category e-commerce platforms, a gym product store, an online grocery service with scheduled delivery, and booking-enabled websites for local businesses.',
        'challenges' => ['Smooth checkout and secure payments', 'Catalogue and order management', 'Delivery and availability rules'],
        'solutions' => ['ecommerce-development', 'website-design-development', 'api-development-integration'],
    ],

    'travel-events' => [
        'title' => 'Travel & Events',
        'icon' => 'calendar',
        'summary' => 'Tour booking websites and event seat-booking systems with admin and operator panels.',
        'intro' => 'Travel and event businesses depend on availability, bookings, and timely information. We have built a tours and travel booking website and an event bus seat-booking system with separate admin and operator access.',
        'challenges' => ['Managing availability and bookings', 'Multiple user roles (admin, operator, customer)', 'Keeping packages and events current'],
        'solutions' => ['web-application-development', 'website-design-development'],
    ],

    'hr-recruitment' => [
        'title' => 'HR & Recruitment',
        'icon' => 'users',
        'summary' => 'Job portals, recruitment management, and employee KYC enrolment systems.',
        'intro' => 'Hiring and workforce onboarding involve many steps and documents. Our work includes a job portal with social login and payments, a recruitment management system, and an employee KYC enrolment system with mobile app APIs.',
        'challenges' => ['High volumes of applications and documents', 'Onboarding and KYC verification', 'Separate experiences for candidates and employers'],
        'solutions' => ['web-application-development', 'api-development-integration', 'custom-software-development'],
    ],

    'saas-business-software' => [
        'title' => 'SaaS & Business Software',
        'icon' => 'layers',
        'summary' => 'AI-powered CRM, task management, content scheduling, and business management platforms.',
        'intro' => 'We build software products that other businesses rely on every day, including an AI sales CRM with n8n automation and Stripe, an AI task manager, an AI LinkedIn content scheduler, a multi-tenant ERP, all-in-one business CRMs, and document automation tools.',
        'challenges' => ['Multi-user, multi-role product design', 'Automation and AI that save real time', 'Subscription payments and integrations'],
        'solutions' => ['ai-integration-automation', 'erp-crm-solutions', 'web-application-development'],
    ],

    'logistics' => [
        'title' => 'Logistics & Transportation',
        'icon' => 'truck',
        'summary' => 'Vehicle tracking, fleet monitoring, and trip management platforms with Google Maps integration.',
        'intro' => 'Logistics runs on knowing where vehicles are and what they are doing. We have built a vehicle tracking and logistics platform with real-time location tracking, route management, and driver and trip workflows on Google Maps APIs.',
        'challenges' => ['No real-time visibility of vehicles', 'Manual route and trip planning', 'Disconnected driver and trip records'],
        'solutions' => ['web-application-development', 'api-development-integration', 'mobile-app-development'],
    ],

    'manufacturing' => [
        'title' => 'Manufacturing',
        'icon' => 'factory',
        'summary' => 'Manufacturing business management systems and websites for manufacturers.',
        'intro' => 'Manufacturers juggle materials, customers, projects, and billing. We have built a manufacturing business management system covering materials through invoicing, and a corporate website for a medicine manufacturer.',
        'challenges' => ['Tracking materials alongside orders and projects', 'Disconnected sales and billing data', 'Presenting products professionally online'],
        'solutions' => ['erp-crm-solutions', 'website-design-development', 'custom-software-development'],
    ],
];
