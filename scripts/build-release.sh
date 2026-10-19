#!/usr/bin/env bash
# Builds a production zip for shared hosting (no Node required on the server).
# Output: build/aparkitsolutions-<date>.zip containing the app with vendor/ (no dev deps).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
STAMP="$(date +%Y%m%d-%H%M)"
OUT="$ROOT/build"
STAGE="$OUT/stage/aparkitsolutions"

cd "$ROOT"
php artisan test
php artisan assets:build

rm -rf "$OUT/stage" && mkdir -p "$STAGE"
rsync -a ./ "$STAGE/" \
  --exclude ".git" --exclude "build" --exclude "node_modules" --exclude "tests" --exclude ".env" \
  --exclude ".phpunit.cache" --exclude "storage/logs/*" --exclude "storage/framework/cache/data/*" \
  --exclude "storage/framework/sessions/*" --exclude "storage/framework/views/*" --exclude "storage/app/private/*" \
  --exclude ".claude" --exclude ".vscode" --exclude ".idea" --exclude ".DS_Store"

cd "$STAGE"
composer install --no-dev --optimize-autoloader --no-interaction --quiet
rm -f bootstrap/cache/*.php   # caches are rebuilt on the server (paths differ)
cd "$OUT/stage"
zip -qr "$OUT/aparkitsolutions-$STAMP.zip" aparkitsolutions
rm -rf "$OUT/stage"
cd "$ROOT" && composer install --quiet   # restore dev dependencies locally
echo "Release ready: build/aparkitsolutions-$STAMP.zip"
