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

## Remaining (owner)
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
