# Architecture

## Structure (only project-specific parts)
```
config/site.php            Company facts, contact, socials, SEO defaults, nav, flags (reads .env)
content/                   File-based CMS
  services.php             9 services (enabled flag, per-service 'tech' list) → /services/{slug}
  projects.php             33 delivered projects → /portfolio/{slug} (images: public/assets/img/portfolio/{slug}.webp, auto-detected)
  industries.php           10 industries (derived from projects) → /industries/{slug}
  tech.php faqs.php team.php testimonials.php
  blog/YYYY-MM-DD-slug.md  Articles (front matter + Markdown)
app/Support/Content.php    Loads content, filters drafts, parses/caches blog Markdown
app/Support/Seo.php        Per-page meta, canonical, robots, OG, JSON-LD graph
app/Http/Controllers/      Page, Service, Portfolio, Industry, Blog, Contact, Seo (sitemap/robots)
app/Http/Middleware/       CanonicalUrl (https + host redirect), SecurityHeaders (CSP etc.)
app/Http/Requests/ContactRequest.php, app/Mail/ContactSubmitted.php
app/helpers.php            asset_v() (mtime cache-busting), whatsapp_url()
routes/web.php             All routes + 301 redirects;  routes/console.php: assets:build
resources/views/
  layouts/app              Single layout; partials/{header,footer,seo,breadcrumbs,pagination,process,tech,hero-art}
  components/              icon, brand, page-hero, cta, faq, field, service-card, project-card, post-card
  pages/ services/ portfolio/ industries/ blog/ errors/ mail/ seo/sitemap
public/assets/             css/app.css (+ app.min.css), js/app.js, fonts (Inter, Montserrat woff2), img/icons.svg sprite, img/brand/*
```

## Rendering & data flow
- Controllers build `$seo = Seo::make(title, description, ['breadcrumbs'=>…])->schema([...])` and pass content arrays to views.
- `AppServiceProvider` shares nav items (sections with no content are hidden) and footer services, defines the `contact` rate limiter, and sets trusted proxies.
- Drafts: `'draft' => true` entries show only with `CONTENT_SHOW_DRAFTS=true`, and always carry `noindex`. They are excluded from the sitemap.
- Blog: parsed Markdown is cached forever, keyed by the file list and mtimes, so editing a file invalidates it automatically. Posts with a future `date:` stay hidden until that date.

## Content editing
- **Service:** edit `content/services.php`. Set `enabled => false` to hide one.
- **Project:** add an entry to `content/projects.php` (title, category, industry, services, summary, overview, features, tech, url, featured). Save a 1280×800 WebP screenshot as `public/assets/img/portfolio/{slug}.webp`. Never add demo credentials or admin URLs.
- **Article:** add `content/blog/2026-11-01-my-slug.md` with front matter: `title, description, date, author, category, tags[, updated, image, image_alt, draft]`.
- **Testimonial or team member:** add entries to `testimonials.php` or `team.php`. Each section appears automatically.

## SEO
Unique title and description, canonical, robots, OG and Twitter tags, plus a JSON-LD `@graph`:
- Organization and WebSite on home
- BreadcrumbList on inner pages
- Service on service pages
- BlogPosting on articles

`/sitemap.xml` and `/robots.txt` are dynamic. Non-indexable environments get `Disallow: /` plus an `X-Robots-Tag` header.

## Security
- CSRF: Laravel default.
- Contact form: honeypot field, encrypted timestamp token (minimum 3 s, maximum 24 h), and rate limits of 5 per 10 minutes and 20 per day per IP.
- Server validation and sanitising; all output escaped.
- Security headers: CSP (`script-src 'self'`), nosniff, frame options, referrer policy, and HSTS in production over HTTPS.
- `.htaccess` blocks dotfiles and sensitive extensions.

## Analytics
GA4 is enabled by `GA_MEASUREMENT_ID` and only when `SITE_INDEXABLE=true`. There's no inline script: `assets/js/analytics.js` reads the `data-ga4` attribute.

## Database
None. Sessions and cache use files. Enquiries are emailed and also appended to `storage/app/private/contact/submissions.jsonl` (configurable).
