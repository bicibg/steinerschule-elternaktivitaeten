#!/bin/bash
cd /home/forge/xn--elternaktivitten-7nb.ch

# Use Forge branch if set, otherwise default to main
BRANCH="${FORGE_SITE_BRANCH:-main}"

# Must be a git repo already (since Forge sometimes skips the first clone)
[ -d .git ] || { echo "Repo not installed here"; exit 1; }

# Update to the exact remote state of the branch (no merges)
git fetch --depth=1 origin "$BRANCH"
git reset --hard "origin/$BRANCH"

# Dependencies
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# (Laravel only)
if [ -f artisan ]; then
  $FORGE_PHP artisan config:cache
  $FORGE_PHP artisan route:cache || true
  $FORGE_PHP artisan view:cache || true
  $FORGE_PHP artisan migrate --force || true
  $FORGE_PHP artisan filament:clear-cached-components || true
fi