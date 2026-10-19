# Project status

_Last updated: 2026-10-19_

## Stage
Implementation complete and verified locally. Waiting on business details before launch.

## Done
- Laravel 13 site with no database or Node; file-based content; all pages: home, about, services (9 + detail), portfolio (filters + detail), industries (+ detail), blog (categories, pagination, related, share), contact, legal ×3, error pages
- Brand: real APARK logos cropped from the brand board (`public/assets/img/brand/`): compact horizontal (header), dark version (footer), AR mark (hero), app icon (favicon, touch icon, schema logo); OG image; brand colours and fonts self-hosted
- Contact form: validation, CSRF, honeypot, time token, rate limit, SMTP mail with Reply-To, JSONL backup
- SEO: see `SEO_CHECKLIST.md`. Security headers, canonical redirect middleware, hardened `.htaccess`
- Release script `scripts/build-release.sh`; docs

## Last verification (2026-10-19)
- `php artisan test`: 35 tests, 199 assertions, all passing
- Apache 2.4 with PHP 8.5 FPM: rewrites, 403 on dotfiles, gzip, caching headers OK; split `public_html` layout OK
- Headless Chrome at 375 / 1440 px: no horizontal overflow, console errors, or failed requests
- Lighthouse (mobile and desktop): Performance 98–100, Accessibility 100, Best Practices 100, SEO 100 (when indexable)

## Pending: needs real company information
- Contact email, phone, WhatsApp, address, and socials (`.env`); SMTP credentials
- Confirm the services list and tech stack; replace SAMPLE projects (`content/projects.php`); publish real industries
- Testimonials and team (only if genuine); legal review → `LEGAL_REVIEWED=true`
- Logo source files: current logos are crops of the brand-board PNG. Supply SVG or high-res PNG originals for sharper rendering (replace the files in `public/assets/img/brand/`, keeping the same names)

## Known limitations
- Logo assets come from a 1536×1024 board image: fine at display sizes, but not vector
- No admin panel by design. Content is edited in `content/` files

## Next recommended task
Fill in production `.env` values, deploy per `DEPLOYMENT.md`, and run its smoke tests.
