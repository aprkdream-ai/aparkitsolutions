<?php

/*
| Portfolio. Keyed by URL slug: /portfolio/{slug}.
| Fields: title, category (filter), industry (content/industries.php slug), services (service slugs),
| summary, overview, features[], tech[], url (public link or null), image (public/assets/img/portfolio), featured.
| Never add demo credentials or admin URLs here. Add 'results' only with verified, client-approved outcomes.
| 'url' is null for internal/login-only systems and for sites that were unreachable at the last check (2026-10-19).
*/

$img = fn (string $slug) => is_file(public_path("assets/img/portfolio/{$slug}.webp")) ? "assets/img/portfolio/{$slug}.webp" : null;

$projects = [

    'bigul' => [
        'title' => 'Bigul',
        'category' => 'Web Applications',
        'industry' => 'fintech',
        'services' => ['web-application-development', 'api-development-integration'],
        'summary' => 'Trading and investment platform with an admin panel, online eKYC journey, and third-party integrations with NSE, BSE, and payment gateways.',
        'overview' => 'Bigul is a trading and investment website for traders, investors, and developers. The platform combines a public website with a full admin panel and a guided eKYC journey for opening trading and demat accounts.',
        'features' => ['Online eKYC / account-opening journey', 'NSE and BSE API integrations with real-time market data', 'Live dashboards and real-time data processing', 'Admin panel to manage content and users', 'Payment gateway integration'],
        'tech' => ['Laravel', 'Node.js', 'Python', 'MongoDB', 'WebSocket', 'REST APIs', 'AWS', 'Payment Gateway'],
        'url' => 'https://bigul.co/',
        'featured' => true,
    ],

    'qonsistic' => [
        'title' => 'Qonsistic',
        'category' => 'SaaS Products',
        'industry' => 'saas-business-software',
        'services' => ['ai-integration-automation', 'erp-crm-solutions', 'custom-software-development'],
        'summary' => 'AI-powered CRM and sales automation platform covering leads, follow-ups, pipelines, proposals, meetings, Stripe payments, and n8n workflows.',
        'overview' => 'Qonsistic is an AI-powered CRM and sales automation platform that helps sales teams find, engage, and convert leads. It brings the full sales cycle into one product, from lead capture to proposals and payments.',
        'features' => ['Lead management and automated follow-ups', 'Sales pipelines and proposals', 'Meeting scheduling', 'Stripe payment integration', 'Workflow automation with n8n', 'Separate super-admin and customer portals'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'Stripe', 'n8n', 'AI Integration'],
        'url' => 'http://qonsistic.aaochaletaxi.com/',
        'featured' => true,
    ],

    'reco' => [
        'title' => 'Reco',
        'category' => 'SaaS Products',
        'industry' => 'fintech',
        'services' => ['custom-software-development', 'web-application-development', 'api-development-integration'],
        'summary' => 'Offline-first accounting and receivables SaaS for invoices, ledgers, expenses, payables, vouchers, bank accounts, and GST reports, with Razorpay and a mobile app.',
        'overview' => 'Reco is an accounting platform that helps businesses track income, expenses, receivables, and payables in one place, with the GST reporting Indian businesses need. A companion mobile app is also available.',
        'features' => ['Offline-first design', 'Invoices, ledgers, income, and expense tracking', 'Receivables, payables, and vouchers', 'Bank account management', 'GST reports and payment workflows', 'Razorpay payment integration', 'Companion mobile app'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'JavaScript', 'REST APIs', 'Razorpay'],
        'url' => 'https://reco.aaochaletaxi.com/',
        'featured' => true,
    ],

    'riffora' => [
        'title' => 'Riffora',
        'category' => 'SaaS Products',
        'industry' => 'saas-business-software',
        'services' => ['ai-integration-automation', 'web-application-development', 'api-development-integration'],
        'summary' => 'AI-powered SaaS that drafts and schedules LinkedIn posts from topics, notes, links, and documents, in English, Hindi, and Hinglish.',
        'overview' => 'Riffora helps professionals, founders, and teams create LinkedIn content faster. Users turn ideas, links, and documents into posts, preview them as they will appear, and publish or schedule them through LinkedIn’s official API.',
        'features' => ['AI post drafting from topics, notes, links, PDFs, DOCX files, and images', 'English, Hindi, and Hinglish support', 'Live LinkedIn-style preview', 'Timezone-aware scheduling with a calendar', 'Publishing via LinkedIn OAuth and official API, with automatic retries', 'Chrome extension'],
        'tech' => ['AI Integration', 'LinkedIn API', 'OAuth', 'Chrome Extension'],
        'url' => 'https://riffora.navayu.tech/',
        'featured' => true,
    ],

    'doctorbuddy' => [
        'title' => 'DoctorBuddy',
        'category' => 'Web Applications',
        'industry' => 'healthcare',
        'services' => ['web-application-development', 'website-design-development'],
        'summary' => 'Appointment booking system that connects doctors with medical representatives (MRs) and streamlines their meetings.',
        'overview' => 'DoctorBuddy bridges the gap between doctors and medical representatives with a structured appointment booking platform, replacing ad-hoc visits with scheduled, manageable meetings.',
        'features' => ['Doctor and MR appointment booking', 'Admin panel for managing users and content', 'Content-managed website with services, blogs, and testimonials'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://doctorbuddy.in/',
        'featured' => true,
    ],

    'estate-nine' => [
        'title' => 'Estate Nine',
        'category' => 'Websites',
        'industry' => 'real-estate',
        'services' => ['website-design-development'],
        'summary' => 'Real estate website for an Indore developer and consultancy, showcasing completed and ongoing projects with enquiry and WhatsApp lead capture.',
        'overview' => 'Estate Nine is a real estate developer and property consultancy in Indore. Its website presents residential and plotted projects with construction progress, along with services, gallery, testimonials, and FAQs.',
        'features' => ['Completed and ongoing project listings with progress', 'Project detail pages', 'Enquiry forms and a WhatsApp project-enquiry flow', 'Services, gallery, testimonials, and FAQ pages', 'Admin panel for content management'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://estatenine.in/',
        'featured' => true,
    ],

    'dodid' => [
        'title' => 'DoDid',
        'category' => 'SaaS Products',
        'industry' => 'saas-business-software',
        'services' => ['ai-integration-automation', 'web-application-development'],
        'summary' => 'AI-integrated task management platform for recurring work, deadlines, and reminders, with a mobile app.',
        'overview' => 'DoDid treats every recurring responsibility as a living commitment, with an owner, due date, and reminders, so teams always know what is due, done, or overdue.',
        'features' => ['Recurring task management', 'Deadlines and automated reminders', 'Workspaces with due-today and timeline views', 'AI integration', 'Companion mobile app'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'AI Integration'],
        'url' => null, // staging domain
    ],

    'nexoracrm' => [
        'title' => 'NexoraCRM',
        'category' => 'CRM & ERP',
        'industry' => 'saas-business-software',
        'services' => ['erp-crm-solutions'],
        'summary' => 'All-in-one business management system for customers, leads, projects, tasks, invoices, payments, estimates, support tickets, and reports.',
        'overview' => 'NexoraCRM centralises day-to-day business operations, from sales and project delivery to billing and customer support, with a separate customer portal.',
        'features' => ['Customers and leads', 'Projects and tasks', 'Estimates, invoices, and payments', 'Support tickets', 'Reports', 'Customer portal'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL'],
        'url' => null, // login-only application
    ],

    'manuflowcrm' => [
        'title' => 'ManuFlowCRM',
        'category' => 'CRM & ERP',
        'industry' => 'manufacturing',
        'services' => ['erp-crm-solutions'],
        'summary' => 'Business management system for manufacturers covering materials, customers, leads, projects, invoices, payments, estimates, support, and reports.',
        'overview' => 'ManuFlowCRM adapts business management to manufacturing, adding materials tracking to customer, project, and billing workflows.',
        'features' => ['Materials management', 'Customers and leads', 'Projects and tasks', 'Estimates, invoices, and payments', 'Support tickets and reports', 'Customer portal'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL'],
        'url' => null, // login-only application
    ],

    'insurance-crm' => [
        'title' => 'Insurance CRM',
        'category' => 'CRM & ERP',
        'industry' => 'fintech',
        'services' => ['erp-crm-solutions', 'custom-software-development'],
        'summary' => 'CRM software built for insurance businesses to manage customers, policies, and follow-ups.',
        'overview' => 'A custom CRM tailored to the workflows of an insurance business, with an admin panel for managing customer and policy information.',
        'features' => ['Customer management', 'Insurance-specific CRM workflows', 'Admin panel'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => null, // unreachable at last check
    ],

    'hospital-data-management-system' => [
        'title' => 'Hospital Data Management System',
        'category' => 'Web Applications',
        'industry' => 'healthcare',
        'services' => ['custom-software-development', 'api-development-integration', 'mobile-app-development'],
        'summary' => 'Healthcare data management system for real-time operational data processing, with REST APIs powering a companion mobile app.',
        'overview' => 'A web-based system that centralises a hospital’s operational data, processes it in real time, and exposes secure APIs consumed by a mobile application.',
        'features' => ['Real-time operational data processing', 'Centralised hospital data management', 'Admin panel with role-based access', 'REST APIs for the mobile app'],
        'tech' => ['Laravel', 'Node.js', 'PHP', 'MySQL', 'WebSocket', 'Bootstrap', 'REST APIs'],
        'url' => null, // internal system
    ],

    'loan-management-system' => [
        'title' => 'Loan Management System',
        'category' => 'Web Applications',
        'industry' => 'fintech',
        'services' => ['custom-software-development', 'api-development-integration', 'mobile-app-development'],
        'summary' => 'Software to manage beneficiary loan data, with APIs for a companion mobile app.',
        'overview' => 'A loan management system that records and manages beneficiary loan data, with secure APIs that let field teams work through a mobile app.',
        'features' => ['Beneficiary and loan data management', 'Admin panel', 'REST APIs for the mobile app'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'REST APIs'],
        'url' => null, // internal system
    ],

    'employee-kyc-enrollment' => [
        'title' => 'Employee KYC Enrollment',
        'category' => 'Web Applications',
        'industry' => 'hr-recruitment',
        'services' => ['custom-software-development', 'api-development-integration', 'mobile-app-development'],
        'summary' => 'Software to manage employee KYC enrolment data, with APIs for a companion mobile app.',
        'overview' => 'An enrolment system that collects and manages employee KYC information through a web admin panel and a mobile app backed by secure APIs.',
        'features' => ['Employee KYC data management', 'Admin panel', 'REST APIs for the mobile app'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'REST APIs'],
        'url' => null, // internal system
    ],

    'quickprint' => [
        'title' => 'QuickPrint',
        'category' => 'Web Applications',
        'industry' => 'saas-business-software',
        'services' => ['custom-software-development'],
        'summary' => 'Tool that turns uploaded Excel sheets into ready-to-print invoices and receipts.',
        'overview' => 'QuickPrint removes repetitive document work: users upload an Excel file and the system generates formatted invoices or receipts for printing.',
        'features' => ['Excel upload and parsing', 'Bulk invoice and receipt generation', 'Print-ready output'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'Excel Import'],
        'url' => null, // login-only application
    ],

    'eventbus' => [
        'title' => 'EventBus',
        'category' => 'Web Applications',
        'industry' => 'travel-events',
        'services' => ['web-application-development'],
        'summary' => 'Event booking system for booking bus seats to events, concerts, and festivals, with admin and operator panels.',
        'overview' => 'EventBus lets customers search events by date and location and book individual bus seats, while operators and administrators manage events and bookings.',
        'features' => ['Event search and seat booking', 'Event categories', 'Admin panel', 'Operator panel'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://eventbus.logiclooms.in/',
    ],

    'getjourney' => [
        'title' => 'GetJourney',
        'category' => 'Websites',
        'industry' => 'travel-events',
        'services' => ['website-design-development', 'web-application-development'],
        'summary' => 'Tours and travel booking website with curated tours, visa information, and an admin panel.',
        'overview' => 'GetJourney presents tour packages and travel services and lets visitors explore and book trips, with everything managed from an admin panel.',
        'features' => ['Tour packages and booking', 'Visa information pages', 'Blog', 'Admin panel'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://getjourney.logiclooms.in/',
    ],

    'ecommerce-platform' => [
        'title' => 'Ecommerce Platform',
        'category' => 'E-commerce',
        'industry' => 'retail-ecommerce',
        'services' => ['ecommerce-development'],
        'summary' => 'Multi-category online store with an admin panel and multiple payment gateway integrations.',
        'overview' => 'A complete e-commerce platform with catalogue, cart, and checkout on the front end and product, order, and campaign management on the back end.',
        'features' => ['Product catalogue and search', 'Cart, wishlist, and compare', 'Multiple payment gateway integrations', 'Admin panel for products and orders'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'Payment Gateway'],
        'url' => 'https://ecommerce.logiclooms.in/',
    ],

    'gym-product-store' => [
        'title' => 'Gym Product Store',
        'category' => 'E-commerce',
        'industry' => 'retail-ecommerce',
        'services' => ['ecommerce-development'],
        'summary' => 'E-commerce website for gym and fitness products with payment gateway integration.',
        'overview' => 'An online store for fitness equipment and gym products, with secure online payments and an admin panel for catalogue and orders.',
        'features' => ['Product catalogue', 'Cart and checkout', 'Payment gateway integration', 'Admin panel'],
        'tech' => ['Laravel', 'PHP', 'MySQL', 'Payment Gateway'],
        'url' => 'https://gymproduct.logiclooms.in/',
    ],

    'savingsmart' => [
        'title' => 'SavingSmart',
        'category' => 'E-commerce',
        'industry' => 'retail-ecommerce',
        'services' => ['ecommerce-development', 'web-application-development'],
        'summary' => 'Online grocery store for fresh groceries and dry fruits with scheduled delivery.',
        'overview' => 'SavingSmart lets customers shop groceries and dry fruits online by category, check delivery availability by location or PIN code, and choose a delivery schedule.',
        'features' => ['Category-based shopping', 'Location / PIN-code delivery check', 'Scheduled delivery', 'Customer accounts'],
        'tech' => ['REST APIs'],
        'url' => 'https://savingsmart.in/',
    ],

    'college-management-system' => [
        'title' => 'College Management System',
        'category' => 'CRM & ERP',
        'industry' => 'education',
        'services' => ['erp-crm-solutions', 'website-design-development'],
        'summary' => 'College management software with an integrated public website.',
        'overview' => 'A management system for a college that combines an informational website (departments, admissions, placements, gallery) with student login and administration tools.',
        'features' => ['College website with academics, admissions, and placements', 'Student login', 'Important alerts and notices', 'Admin panel'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL'],
        'url' => 'https://college.logiclooms.in/',
    ],

    'school-management-system' => [
        'title' => 'School Management System',
        'category' => 'CRM & ERP',
        'industry' => 'education',
        'services' => ['erp-crm-solutions'],
        'summary' => 'School ERP for managing teachers, events, admissions, and day-to-day school administration.',
        'overview' => 'A school ERP with a public website for admissions, teachers, and events, plus a back office for administrators.',
        'features' => ['Online admission', 'Teachers and events', 'School administration modules', 'Admin authentication'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL'],
        'url' => 'https://school.logiclooms.in/',
    ],

    'tutora-job-portal' => [
        'title' => 'Tutora Job Portal',
        'category' => 'Web Applications',
        'industry' => 'hr-recruitment',
        'services' => ['web-application-development', 'api-development-integration'],
        'summary' => 'Job portal with social media login and payment gateway integration.',
        'overview' => 'Tutora connects job seekers and employers with job search, résumé features, and paid plans, plus quick sign-up through social media accounts.',
        'features' => ['Job search by category and location', 'Social media login', 'Résumé and job pages', 'Payment gateway for plans', 'Admin panel'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL', 'Social Login', 'Payment Gateway'],
        'url' => 'https://tutora.logiclooms.in/',
    ],

    'talentstack' => [
        'title' => 'TalentStack',
        'category' => 'Web Applications',
        'industry' => 'hr-recruitment',
        'services' => ['web-application-development'],
        'summary' => 'Recruitment management system for job listings, candidate applications, and hiring workflows.',
        'overview' => 'TalentStack helps organisations manage recruitment, from publishing jobs to tracking candidate applications.',
        'features' => ['Job listings with keyword, location, and experience search', 'Candidate accounts and applications', 'Admin panel'],
        'tech' => ['CodeIgniter', 'PHP', 'MySQL'],
        'url' => null,
    ],

    'pharmiza' => [
        'title' => 'Pharmiza',
        'category' => 'Websites',
        'industry' => 'manufacturing',
        'services' => ['website-design-development'],
        'summary' => 'Website for a healthcare and medicine manufacturer, with an admin panel.',
        'overview' => 'A corporate website for a medicine manufacturer that presents its products and company information, managed through an admin panel.',
        'features' => ['Product and company pages', 'Admin panel for content'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => null, // staging domain
    ],

    'freedom-from-diabetes' => [
        'title' => 'Freedom From Diabetes',
        'category' => 'Websites',
        'industry' => 'healthcare',
        'services' => ['website-design-development'],
        'summary' => 'Dynamic, content-managed informational website for a diabetes reversal programme.',
        'overview' => 'A dynamic informational website presenting programmes, research, lifestyle resources, and events, all manageable without code changes.',
        'features' => ['Dynamic content management', 'Programme and event pages', 'Research and blog sections'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://www.freedomfromdiabetes.org/',
    ],

    'interior-design-website' => [
        'title' => 'Interior Design Website',
        'category' => 'Websites',
        'industry' => 'real-estate',
        'services' => ['website-design-development'],
        'summary' => 'Interior design studio website with services, projects, and an admin panel.',
        'overview' => 'A visually led website for an interior design business, showcasing services and projects, with content managed from an admin panel.',
        'features' => ['Services and project showcase', 'Enquiry / contact', 'Admin panel'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://interior.logiclooms.in/',
    ],

    'property-builder-website' => [
        'title' => 'Property Builder Website',
        'category' => 'Websites',
        'industry' => 'real-estate',
        'services' => ['website-design-development', 'web-application-development'],
        'summary' => 'Property listing website for a builder, with property search and an admin panel.',
        'overview' => 'A property website listing residential and commercial projects with search filters for location, type, possession, and budget.',
        'features' => ['Property search with multiple filters', 'Residential, commercial, and plot listings', 'Admin panel for listings'],
        'tech' => ['Laravel', 'PHP', 'MySQL'],
        'url' => 'https://properties.logiclooms.in/',
    ],

    'parlour-website' => [
        'title' => 'Parlour Website',
        'category' => 'Websites',
        'industry' => 'retail-ecommerce',
        'services' => ['website-design-development'],
        'summary' => 'Beauty parlour website with services, pricing, gallery, booking, and an admin panel.',
        'overview' => 'A website for a beauty parlour presenting services, pricing, and gallery, with appointment booking and content management.',
        'features' => ['Services and pricing', 'Gallery and testimonials', 'Appointment booking', 'Admin panel'],
        'tech' => ['Zend Framework', 'PHP', 'MySQL'],
        'url' => 'https://parlour.hrrswai.com/',
    ],
    'aero-erp' => [
        'title' => 'AERO ERP',
        'category' => 'CRM & ERP',
        'industry' => 'saas-business-software',
        'services' => ['erp-crm-solutions', 'custom-software-development', 'cloud-deployment-consulting'],
        'summary' => 'Scalable multi-tenant ERP platform with complex business workflows, role-based access control, and automation.',
        'overview' => 'AERO ERP is a multi-tenant ERP platform where each organisation works in its own secure space. It handles complex business workflows through modular back-end services and a modern web front end.',
        'features' => ['Multi-tenant architecture', 'Back-end modules for complex business workflows', 'Authentication and role-based access control (RBAC)', 'Workflow automation', 'REST APIs'],
        'tech' => ['Python', 'Django', 'React.js', 'Next.js', 'PostgreSQL', 'Redis', 'Docker', 'AWS', 'REST APIs'],
        'url' => null,
    ],

    'verifyfirst' => [
        'title' => 'VerifyFirst',
        'category' => 'SaaS Products',
        'industry' => 'fintech',
        'services' => ['ai-integration-automation', 'api-development-integration'],
        'summary' => 'KYC and document verification platform with OCR, AI-assisted document processing, and secure APIs.',
        'overview' => 'VerifyFirst automates identity and document checks. Documents are processed with OCR and AI, validated, and exposed through secure back-end APIs for integration with other systems.',
        'features' => ['KYC and document verification workflows', 'OCR document processing', 'AI / LLM-assisted validation', 'Secure back-end APIs and third-party integrations'],
        'tech' => ['Python', 'Flask', 'MySQL', 'OCR', 'AI / LLM', 'REST APIs'],
        'url' => null,
    ],

    'mitraservices' => [
        'title' => 'MitraServices',
        'category' => 'Web Applications',
        'industry' => 'logistics',
        'services' => ['web-application-development', 'api-development-integration'],
        'summary' => 'Vehicle tracking and logistics platform for real-time fleet monitoring, routes, drivers, and trips.',
        'overview' => 'MitraServices gives logistics teams real-time visibility of their fleet. It combines live vehicle location tracking with route, driver, and trip management on scalable back-end services.',
        'features' => ['Real-time vehicle location tracking', 'Route management and distance calculation', 'Vehicle, driver, and trip workflows', 'Google Maps integration for maps, geolocation, and routes', 'Back-end APIs and location-based services'],
        'tech' => ['Laravel', 'MySQL', 'JavaScript', 'Bootstrap', 'Google Maps APIs'],
        'url' => null,
    ],

    'shrigoda-crm' => [
        'title' => 'Shrigoda CRM',
        'category' => 'CRM & ERP',
        'industry' => 'saas-business-software',
        'services' => ['erp-crm-solutions'],
        'summary' => 'Scalable CRM with lead management, customer management, workflow automation, and reporting.',
        'overview' => 'Shrigoda CRM streamlines lead tracking and customer operations with database-driven workflows and reports that keep the team aligned.',
        'features' => ['Lead management', 'Customer management', 'Workflow automation', 'Reporting'],
        'tech' => ['Laravel', 'MySQL', 'JavaScript', 'Bootstrap'],
        'url' => null,
    ],

    'multi-vendor-ecommerce' => [
        'title' => 'Multi-Vendor eCommerce Platform',
        'category' => 'E-commerce',
        'industry' => 'retail-ecommerce',
        'services' => ['ecommerce-development', 'api-development-integration'],
        'summary' => 'B2B/B2C multi-vendor marketplace with vendor, catalogue, customer, and order management and multiple payment gateways.',
        'overview' => 'A marketplace where multiple vendors sell to businesses and consumers. Vendors manage their own catalogues and orders, while the platform handles customers, payments, and operations.',
        'features' => ['B2B and B2C multi-vendor marketplace', 'Vendor, product/catalogue, customer, and order management', 'REST APIs for product, order, and vendor operations', 'Stripe, Razorpay, and PayPal payments'],
        'tech' => ['Node.js', 'Laravel', 'React.js', 'MySQL', 'REST APIs', 'Stripe', 'Razorpay', 'PayPal'],
        'url' => null,
    ],
];

foreach ($projects as $slug => &$p) {
    $p += ['image' => $img($slug), 'image_alt' => $p['title'].' homepage screenshot', 'client' => null, 'results' => [], 'featured' => false];
}

return $projects;
