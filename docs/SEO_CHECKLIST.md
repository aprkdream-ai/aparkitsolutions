# SEO checklist

## Implemented (verified by tests and Lighthouse)
- [x] Unique title and meta description per page (service titles checked by tests); one H1 per page; logical H2/H3
- [x] Canonical on every page (`?page=N` self-canonical; filters and categories noindexed)
- [x] Robots meta plus `X-Robots-Tag` on non-production; dynamic `/robots.txt`
- [x] Dynamic `/sitemap.xml`: home, services, about, contact, blog, articles. Drafts, empty sections, and unreviewed legal pages are excluded
- [x] OG and Twitter tags; default 1200×630 image `public/assets/img/og-default.png`; article OG metadata
- [x] JSON-LD: Organization (with PostalAddress), WebSite, ProfessionalService/LocalBusiness (verified address and geo, home and contact), BreadcrumbList, Service, BlogPosting. No fabricated ratings
- [x] Breadcrumbs (visible plus schema); internal links between services, related services, articles, and contact
- [x] 301 redirects: `/contact-us`, `/about-us`, `/insights`, trailing slash, `/index.php/*`; HTTPS and host redirect via `FORCE_CANONICAL_URL`
- [x] Custom 404/419/429/500/503 pages (noindex)
- [x] Lighthouse 12 run locally against Apache with PHP-FPM (2026-10-19): Performance 98–100, Accessibility 100, Best Practices 100, SEO 100 (with indexing enabled)

## SEOptimer audit fixes (2026-10-20)
- [x] Titles 30–60 and descriptions 120–160 on all 65 pages (`Seo::fullTitle()` drops the brand suffix when too long; `Seo::fitDescription()` extends or trims). Enforced by `SeoAuditTest`
- [x] Home keyword alignment: title, description, H1 kicker, and H2s use "custom software development"; marquee and tile label noise removed
- [x] Email privacy: emails are HTML-entity encoded (`email_obfuscate()`), and "@" is escaped in JSON-LD
- [x] Address: `<address>` with schema.org PostalAddress microdata (footer and contact), ending in "India"
- [x] `/llms.txt`, generated from content
- [x] Render-blocking CSS removed (CSS inlined, `INLINE_CSS`); GA loads on first interaction or after 5 s
- [x] Public pages are session-less (no cookies) with `Cache-Control: public, s-maxage=600`; the contact routes keep their session
- [x] HSTS header includes `preload`

## Seobility-style checks (2026-10-20)
- [x] Home headings 57 → 10: card, step and footer labels and home card titles are styled `<p>` elements (`tag="p"` on `x-service-card`/`x-project-card`/`x-post-card`; listing pages keep `h3`). Enforced by test (≤14)
- [x] All images have descriptive alt text; the logo alt is the link text (header and footer differ)
- [x] Social sharing: `partials/share.blade.php` (LinkedIn, X, Facebook, WhatsApp) in the footer site-wide, and in the article on blog posts (footer copy hidden there)
- [x] Anchor texts: no empty anchors (icon links have visually hidden text); unique CTA wording per page; footer uses short service labels (`short` in content/services.php) and footer nav labels (`footer` in config/site.nav). Only the nav "Home" + breadcrumb "Home" repeats remain on inner pages (standard)

## Remaining (owner)
- [ ] HSTS preload: after confirming every subdomain works over HTTPS, submit aparkitsolutions.com at https://hstspreload.org. This removes the http→https redirect for Chrome, Edge and Firefox. Hard to undo
- [ ] Link building (Links grade D+): Google Business Profile; Clutch, GoodFirms, DesignRush, Justdial, IndiaMART listings; ask clients for a "Developed by APARK IT Solutions" footer link (Estate Nine already has one); LinkedIn company posts linking case studies
- [ ] Optional: Instagram and YouTube profiles (add the URLs to `SOCIAL_INSTAGRAM` / `SOCIAL_YOUTUBE`). Facebook Pixel is only worth it when running Facebook ads
- [ ] Set `APP_URL` to the final https domain and `SITE_INDEXABLE=true` in production
- [ ] Verify Search Console (`SEO_GOOGLE_VERIFICATION`) and Bing; submit the sitemap
- [ ] Create or claim the Google Business Profile for the Waidhan address (matches the LocalBusiness schema)
- [ ] Optional: add more socials and `SEO_AREA_SERVED`
- [ ] Publish real case studies and the industries actually served
- [ ] Review legal pages, then set `LEGAL_REVIEWED=true` (makes them indexable and adds them to the sitemap)
- [ ] Publish articles regularly (1–2 per month); add WebP feature images where useful
- [ ] Validate live pages with Google's Rich Results Test and https://validator.schema.org

## Verify after deployment
`curl -sI https://domain/` (200 response, HSTS header) · `curl -s https://domain/robots.txt` · open `/sitemap.xml` · view the source for canonical/robots
