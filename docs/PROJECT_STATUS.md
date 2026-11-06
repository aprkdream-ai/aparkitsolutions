# Project status

_Last updated: 2026-11-06_

## Stage
Live on Hostinger (aparkitsolutions.com, PHP 8.3, behind the Hostinger CDN, SSL active). Enterprise repositioning (2026-11-06) is built and verified locally. **Next: owner review, then deploy** (see that section below).

## Done
- Laravel 13 site, no database or Node; all pages (home, about, 11 services, portfolio, 9 industries, blog, contact, legal, errors)
- Real content: 34 projects (28 from the owner's list + 5 from Anil's resume: AERO ERP, VerifyFirst, MitraServices, Shrigoda CRM, Multi-Vendor eCommerce; MediaFlow added 2026-11-03; 18 with screenshots), 10 industries, tech stack from projects + resume + React Native (Expo) and Flutter (owner request), per-service technology lists, team bio from resume
- Contact: info@aparkitsolutions.com, +91 90094 34118 (phone and WhatsApp), LinkedIn; address College Road, Bilaunji, Waidhan, MP 486886 with a Google Map on the contact page; LocalBusiness (ProfessionalService) JSON-LD with geo
- Contact form: AJAX submit (`form[data-ajax]` in app.js; JSON 200/422/429/419/503; no-JS fallback = normal POST + redirect). Self-hosted math captcha (`app/Support/Captcha.php`): per-form encrypted token (answer + nonce + time), single use via a cache nonce, 2h expiry, accepts Devanagari digits. Not session-based, so multiple tabs or prefetch don't break it. Refresh endpoint `/contact/captcha`. GA event `generate_lead` on success. Visitor auto-reply `App\Mail\ContactAcknowledgement` (CONTACT_AUTOREPLY, sent via `defer()` after the response; never echoes the free-text message, to prevent spam relay)
- Layout: container 1360px; card grids fixed at 3 columns on desktop
- Brand logos cropped from the brand board; OG image
- Redesigned sections: process timeline, 8-group tech grid, industry tiles (home) and cards (industries page), numbered benefit cards, simple team cards (photo, name, designation; 3 per row). Team from aparkitsolutions.com: Anil Prajapati, Rubina Khatoon, Mamta Pandey. The footer was kept in its original design at the owner's request
- Inner-page hero redesigned (`components/page-hero.blade.php`): dark teal gradient, grid and AR-style arc, optional `<x-slot:aside>` panel (service tech, project screenshot in a browser frame, real stats, contact links, icon grids)
- SMTP: Gmail / Google Workspace (info@aparkitsolutions.com). A real test email was sent successfully on 2026-10-19
- `.env.production` (git-ignored, contains secrets) is shipped as `.env` by `scripts/build-release.sh`

## Deliberately excluded
- Demo credentials, admin and login URLs from the project list
- Links to dev/test or login-only systems, and to sites unreachable at the check (dev.pharmiza.in SSL error, insurance.adsinfotech.biz refused, talentstack loads images from localhost)
- Unverified stats, testimonials, address; from the resume: personal email, education, employer names, the "30% performance" claim

## Last verification (2026-10-19)
- 69 tests, 579 assertions, all passing; no credentials or admin URLs in rendered pages (tested)
- Lighthouse (mobile): Performance 97–100, Accessibility 100, Best Practices 100; SEO 100 when indexable
- No overflow, console errors, or failed requests at 375 / 1440 px

## Analytics & social
- GA4 `G-ZMXXSE5Y06` (`GA_MEASUREMENT_ID`, production only): loaded by `public/assets/js/analytics.js` after page load; CSP allows GTM/GA hosts only when configured; Cookie and Privacy pages list the GA cookies automatically
- Socials: LinkedIn company `apark-it-solutions`, Facebook profile `61583467928810`, Instagram `@apark_it_solution`. X/Twitter is disabled for now (no profile icon, no share button) at the owner's request. The old facebook.com/aparkitsolutions does not exist and `contactPoint` (customer service, IN, English/Hindi)

## SEO audit (SEOptimer, 2026-10-20)
Before: A- overall (On-page A+, GEO A+, Performance A-, Usability C, Links D+). All code-side items are fixed; see `SEO_CHECKLIST.md`. Local Lighthouse desktop: Performance 100, no render-blocking resources.

## Performance notes
- Scroll smoothness (2026-10-19): removed the header `backdrop-filter`, scroll-reveal animations, and the SVG orbit rotation; the marquee pauses off-screen; hover is suspended while scrolling (`html.is-scrolling`); cards use 640px thumbnails (`*-640.webp`). Worst frame on home dropped from 583ms to ~50ms with a 4x CPU throttle. Don't reintroduce blur on sticky elements or scroll-triggered motion.

## PWA
- `/manifest.json` (route `manifest` in SeoController) at the same URL the old site used, so phones with the old site installed receive the new icons. Icons are `assets/img/brand/icon-192.png`, `icon-512.png` and `icon-maskable-512.png` (AR mark on white; maskable sized for the 80% safe zone). There's no service worker (none needed for installability). Chrome validated: 0 manifest errors, 0 installability errors.

## Mobile notes
- `body { overflow-x: clip }`, never `hidden`: `hidden` breaks the sticky header in some mobile browsers and WebViews. The viewport meta has `viewport-fit=cover` and the sticky header pads for `env(safe-area-inset-top)`, with a teal strip behind the status bar (Android Chrome draws edge-to-edge; without this the header slid under the status bar). Tested with CDP `Emulation.setSafeAreaInsetsOverride`. Grids use `minmax(0, 1fr)` so long text can't push pages wider than 320px (verified 320–768px).

## Pending
- **DNS (owner):** SPF should include Google, since mail is sent via smtp.gmail.com: `v=spf1 include:_spf.google.com include:_spf.mail.hostinger.com ~all` (DKIM google._domainkey is already present)
- **Team photo:** save it as `public/assets/img/team/anil-prajapati.webp` (portrait 4:5, e.g. 480×600). Add other members to `content/team.php`. The card shows initials until the file exists
- Upload and run the smoke tests in `DEPLOYMENT.md` §6 (no hosting access in the dev session)
- Legal review → `LEGAL_REVIEWED=true` in `.env`
- Optional: original vector logo files; testimonials; office address (enables map); Search Console verification
- Note (local only): this Mac's IPv6 route to smtp.gmail.com times out, so local mail sends may hang; production is unaffected

## Next recommended task
Owner review of the items under "Enterprise repositioning", then deploy per `DEPLOYMENT.md`.

## SEO & conversion pass (2026-11-03)
- Home hero: descriptive H1 "Custom Software Development for Growing Businesses"; the slogan became the kicker `<p>` (no longer inside the H1); secondary CTA "View Our Work" → portfolio. Home service grid shows the first 9 services (3×3); `/services` lists all
- New services: `saas-product-development` (tagged on the 5 "SaaS Products" projects) and `software-maintenance-support` (no projects tagged: none are verified as maintenance work). Both appear in the nav, sitemap, llms.txt and contact form automatically
- Blog: `services:` front matter (comma-separated slugs) adds "Related services" and "Related projects" aside cards (`BlogController::show`)
- 70 tests, 597 assertions passing; no overflow at 320/375 px; JSON-LD parses on changed pages
- Local PHP: the Herd `php83` shim execs itself in a loop; use `/usr/local/bin/php` (8.4) for artisan/tests

## Enterprise repositioning (2026-11-06)
- Positioning: custom software engineering and business automation. Home H1 "We Build Intelligent Software Systems That Power Your Business."; keyword kept in the kicker, title, and H2. CTAs → `/project-brief`: hero "Discuss Your Project", header "Start a Project", CTA band "Send Your Project Brief" (labels differ so anchors stay unique)
- Nav: Services, Solutions, Industries, Portfolio, Insights, About, Contact (Home removed; the logo links home). Collapses below 1200px because of the longer CTA. Desktop links are centred between the logo and CTA; the open menu uses 2 columns from 640px
- Design: `--navy` is now #081E3E; home and inner heroes and the CTA band use `--grad-navy`. Teal stays the accent. Footer design unchanged (owner preference); only its text and one link changed
- Home order: hero (inline SVG diagram, no image) → company overview → challenges → grouped services → industries → case studies → approach → technology → why APARK → testimonials (hidden while empty) → insights → FAQ → CTA. Marquee, float cards, and highlights strip removed
- New `/solutions` (`content/solutions.php`): challenges mapped to services and real projects; `#slug` anchors link from home and service pages. Service grouping is in `content/service-groups.php`
- New `/project-brief` (`ProjectBriefController`, `ProjectBriefRequest`): 3 steps, all in the HTML so it works without JS. Reuses captcha, honeypot, timing check, rate limit, backup, and mail. `/contact` is unchanged as the simple alternative. `?service=` and `?industry=` prefill it
- GA events: `generate_lead` (`form`: contact or project_brief), `project_brief_complete`, `click_to_call`, `whatsapp_click`. No form data is sent. Mark them as key events in GA4
- `partials.process` now shows the six discovery-first stages site-wide
- 2 new articles; `challenge`/`solution` added to 7 featured projects, restating their documented overview and features only

### Production bug fixed: `/public/` duplicate site
The host rewrites into `/public`, so `/public/*` served an indexable, self-canonical copy of every page, and trailing-slash URLs 301'd into it. Fixed in `public/.htaccess` (uses THE_REQUEST) and in `CanonicalUrl` (base path check). Verified on Apache with a simulated root rewrite: one hop, no loops. Live until deployed.

### Verification (2026-11-06, local)
- 91 tests, 709 assertions passing; Pint clean; release zip builds with no dev packages
- Crawl: 103 pages, 48 assets, 0 broken links, 1 H1 each, canonicals correct, JSON-LD parses, sitemap 72 URLs, no duplicates
- No page overflow at 320/375/768/1100/1440 px; no console errors; brief tested end to end in Chrome, including no-JS
- Lighthouse mobile, median of 3, same machine, gzip on, before → after: home 99 → 100 (LCP 1958 → 1805 ms, 143 → 134 KB, FCP 829 → 930 ms), service 100 → 100, portfolio 97 → 97, contact 100 → 100. New pages: `/solutions` 100, `/project-brief` 100. Accessibility, Best Practices, SEO 100 throughout. CLS 0
- Not yet measured on production. Compare gzip on: without it, the inline CSS makes results misleading

### Owner to confirm
- `legacy-software-modernization` and `dedicated-development-teams` are `'draft' => true` in `content/services.php`. Remove the flag if offered; home, nav groups, sitemap, and forms pick them up automatically
- Not on the site because they are not in the verified stack: .NET, Vue.js, SQL Server, Google Cloud, Azure, OpenAI by name. Add to `content/tech.php` only if genuine
- Review the `challenge` and `solution` text in `content/projects.php`
- DoctorBuddy links to `uat.doctorbuddy.in` with a screenshot from it, at the owner's request (2026-11-06). The UAT site is patient-facing, while the case study text describes doctor and MR meetings: update the text if the product has changed, and switch the link to production when ready
- Qonsistic's public link is `qonsistic.aaochaletaxi.com` over http: replace it if there is a production domain

## Testing notes
- Ports 8000 and 8001 are used by other local apps
- A/B against an old copy needs a real `vendor/` copy: a symlink makes Composer load the current `app/` classes
- Single Lighthouse runs can be wildly wrong (one production run showed TBT 9.8 s; reruns 10–20 ms). Use the median of several
