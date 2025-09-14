#!/bin/bash
cd /home/forge/xn--elternaktivitten-7nb.ch

# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets if needed
if [ -f package.json ]; then
    npm ci --production
    npm run build
fi

# Restart queue workers if using queues
php artisan queue:restart || true