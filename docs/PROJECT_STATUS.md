# Project status

_Last updated: 2026-10-19_

## Stage
Content complete, release built and verified locally. **Next: upload to Hostinger** (aparkitsolutions.com, PHP 8.3, behind the Hostinger CDN, SSL active).

## Done
- Laravel 13 site, no database or Node; all pages (home, about, 9 services, portfolio, 9 industries, blog, contact, legal, errors)
- Real content: 33 projects (28 from the owner's list + 5 from Anil's resume: AERO ERP, VerifyFirst, MitraServices, Shrigoda CRM, Multi-Vendor eCommerce; 17 with screenshots), 10 industries, tech stack from projects + resume + React Native (Expo) and Flutter (owner request), per-service technology lists, team bio from resume
- Contact: info@aparkitsolutions.com, +91 90094 34118 (phone and WhatsApp), LinkedIn; address College Road, Bilaunji, Waidhan, MP 486886 with a Google Map on the contact page; LocalBusiness (ProfessionalService) JSON-LD with geo
- Contact form: AJAX submit (`form[data-ajax]` in app.js; JSON 200/422/429/419/503; no-JS fallback = normal POST + redirect). Self-hosted math captcha (`app/Support/Captcha.php`): per-form encrypted token (answer + nonce + time), single use via a cache nonce, 2h expiry, accepts Devanagari digits. Not session-based, so multiple tabs or prefetch don't break it. Refresh endpoint `/contact/captcha`. GA event `generate_lead` on success. Visitor auto-reply `App\Mail\ContactAcknowledgement` (CONTACT_AUTOREPLY, sent via `defer()` after the response; never echoes the free-text message, to prevent spam relay)
- Layout: container 1360px; card grids fixed at 3 columns on desktop; home has a project marquee and a stat row
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
- 66 tests, 530 assertions, all passing; no credentials or admin URLs in rendered pages (tested)
- Lighthouse (mobile): Performance 97–100, Accessibility 100, Best Practices 100; SEO 100 when indexable
- No overflow, console errors, or failed requests at 375 / 1440 px

## Analytics & social
- GA4 `G-ZMXXSE5Y06` (`GA_MEASUREMENT_ID`, production only): loaded by `public/assets/js/analytics.js` after page load; CSP allows GTM/GA hosts only when configured; Cookie and Privacy pages list the GA cookies automatically
- Organization schema: `sameAs` (LinkedIn, Facebook, X company pages) and `contactPoint` (customer service, IN, English/Hindi)

## SEO audit (SEOptimer, 2026-10-20)
Before: A- overall (On-page A+, GEO A+, Performance A-, Usability C, Links D+). All code-side items are fixed; see `SEO_CHECKLIST.md`. Local Lighthouse desktop: Performance 100, no render-blocking resources.

## Performance notes
- Scroll smoothness (2026-10-19): removed the header `backdrop-filter`, scroll-reveal animations, and the SVG orbit rotation; the marquee pauses off-screen; hover is suspended while scrolling (`html.is-scrolling`); cards use 640px thumbnails (`*-640.webp`). Worst frame on home dropped from 583ms to ~50ms with a 4x CPU throttle. Don't reintroduce blur on sticky elements or scroll-triggered motion.

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
Upload `build/aparkitsolutions-*.zip` per `DEPLOYMENT.md`, then submit `https://aparkitsolutions.com/sitemap.xml` to Search Console.
