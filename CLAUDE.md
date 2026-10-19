# APARK IT Solutions — corporate website

Read this first, then `docs/PROJECT_STATUS.md`. Open other docs only for the section you need:
[ARCHITECTURE](docs/ARCHITECTURE.md) · [DEPLOYMENT](docs/DEPLOYMENT.md) · [SEO_CHECKLIST](docs/SEO_CHECKLIST.md)

## Stack (confirmed)
- Laravel 13, PHP ^8.3, server-rendered Blade. **No database** (file session/cache drivers).
- Content is file-based: `content/*.php` (services, projects, industries, tech, faqs, team, testimonials) and `content/blog/*.md`.
- Company facts, contact details, socials, SEO defaults, and feature flags: `config/site.php`, which reads `.env`.
- Front end is hand-written CSS/JS in `public/assets`. **No Node/Vite.** CSS is minified with `php artisan assets:build`.
- Target: shared hosting (Hostinger/cPanel). Nothing may require a long-running process.

## Rules
- Never invent business facts (clients, results, reviews, team, address, phone). Unverified content is marked `'draft' => true`. Drafts are hidden unless `CONTENT_SHOW_DRAFTS=true`, and are always noindexed.
- Empty config values hide their UI (WhatsApp, map, socials, testimonials, team).
- Escape output (`{{ }}`). Only Markdown rendered from `content/blog` uses `{!! !!}`.
- Edit `public/assets/css/app.css`, then run `php artisan assets:build`. Never hand-edit `app.min.css`.
- Keep docs short and update `docs/PROJECT_STATUS.md` after each work stage.
- Token economy: use targeted reads (grep, line ranges) and small edits. Don't dump whole files or trees.

## Commands
```sh
php artisan serve                 # local dev at http://127.0.0.1:8000
php artisan test                  # feature tests (tests/Feature)
php artisan assets:build          # minify CSS → public/assets/css/app.min.css
bash scripts/build-release.sh     # production zip in build/ (see docs/DEPLOYMENT.md)
```
