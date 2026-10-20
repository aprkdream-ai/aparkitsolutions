# Deployment: shared hosting (Hostinger, cPanel)

## Requirements
- PHP **8.3+**, with extensions: ctype, curl, fileinfo, mbstring, openssl, tokenizer, xml, dom, filter. Tested locally on PHP 8.4 (CLI) and 8.5 (FPM with Apache).
- Apache or LiteSpeed with `.htaccess` (mod_rewrite). No database, Node.js, or cron is needed.

## 1. Build locally
```sh
bash scripts/build-release.sh      # runs tests, minifies CSS, composer --no-dev → build/aparkitsolutions-<stamp>.zip
```

## 2. Upload. Choose one layout.

**A. Recommended: document root points to `public/`**
1. Upload the zip to the account home directory (e.g. `~/domains/example.com/`) and extract it, giving `~/domains/example.com/aparkitsolutions/`.
2. Point the domain's document root at `aparkitsolutions/public`. Either:
   - in hPanel/cPanel, change the domain's document root, or
   - over SSH: `rm -rf public_html && ln -s aparkitsolutions/public public_html` (back up `public_html` first).

**B. No document-root control and no SSH**
1. Extract `aparkitsolutions/` **next to** `public_html` (same parent folder).
2. Copy everything inside `aparkitsolutions/public/`, including `.htaccess`, into `public_html/`.
3. `public_html/index.php` automatically finds the app at `../aparkitsolutions`.
4. **Important:** re-copy `public/` after every update that changes assets.

Never place the whole project inside `public_html`: `.env` and source files would become reachable.

## 3. Configure `.env` on the server
**aparkitsolutions.com:** the zip already contains the production `.env` (copied from the local, git-ignored `.env.production`): APP_URL `https://aparkitsolutions.com`, Gmail SMTP for info@aparkitsolutions.com, `TRUSTED_PROXIES=*` (the site is behind the Hostinger CDN), and `FORCE_CANONICAL_URL=true` (www → non-www). Keep the zip private.

For any other environment, copy `.env.example` to `.env`, then:
- `APP_URL=https://www.your-domain.com` (the exact canonical host)
- `APP_KEY`: run `php artisan key:generate`, or generate one locally and paste it in
- `CONTACT_EMAIL`, `CONTACT_RECIPIENT`, plus phone, WhatsApp, address, and socials if verified (empty values hide their UI)
- SMTP: `MAIL_HOST=smtp.hostinger.com`, `MAIL_PORT=465`, `MAIL_SCHEME=smtps`, `MAIL_USERNAME`/`MAIL_PASSWORD` for a mailbox on the domain, `MAIL_FROM_ADDRESS` set to that same mailbox
- Keep `APP_DEBUG=false`, `CONTENT_SHOW_DRAFTS=false`, `SITE_INDEXABLE=true` (use `false` on staging)
- `FORCE_CANONICAL_URL=true` **only after** HTTPS works (this redirects http→https and non-canonical hosts)
- Behind Cloudflare or another proxy: set `TRUSTED_PROXIES=*` so HTTPS is detected without redirect loops

## 4. Permissions and caches (SSH or the hosting terminal)
```sh
chmod -R 775 storage bootstrap/cache
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
Without SSH, skip the caches; the site works uncached. After any `.env` change, re-run `php artisan config:cache` (or delete `bootstrap/cache/config.php`).

## 5. HTTPS
Enable the free SSL in hPanel/cPanel, confirm `https://` loads, then set `FORCE_CANONICAL_URL=true`. Optionally uncomment the HTTPS block in `public/.htaccess` as well. Test both `http://` and the `www`/non-`www` variants: each should 301 once to the canonical URL.

## 6. Smoke tests
- [ ] Home, `/services`, one service page, `/blog`, one article, `/contact`, and a random URL (should be a 404 page) all load
- [ ] Submit the contact form and confirm the email arrives, with Reply-To set to the visitor
- [ ] `/robots.txt` shows `Sitemap:`, `/sitemap.xml` lists URLs on the https canonical host
- [ ] `/.env` returns 403/404
- [ ] View the page source: `<meta name="robots" content="index, follow…">` and the canonical uses https
- [ ] Submit the sitemap in Google Search Console

## Troubleshooting
- **"Composer dependencies require a PHP version >= X":** the server's PHP is older than the build target. Keep `config.platform.php` at 8.3.0 in `composer.json`, run `composer update`, and rebuild. Or raise the PHP version in hPanel.
- **500 error:** check `storage/logs/laravel-*.log`, confirm `APP_KEY` is set, `storage` is writable, and PHP is 8.3+.
- **Redirect loop:** set `TRUSTED_PROXIES=*` (behind a proxy), or set `FORCE_CANONICAL_URL=false`.
- **Email not sending:** check the SMTP credentials and log. Enquiries are still saved to `storage/app/private/contact/submissions.jsonl`.
- **Old CSS showing:** run `php artisan assets:build` locally and re-upload `public/assets/css/app.min.css`.
- **419 on form:** the session expired. Check that `storage/framework/sessions` is writable and `SESSION_SECURE_COOKIE=true` is used only on HTTPS.

## Updating
Rebuild the zip and upload the changed files, or the whole `aparkitsolutions/` folder while keeping `.env` and `storage/`. Then re-run the step 4 cache commands.
