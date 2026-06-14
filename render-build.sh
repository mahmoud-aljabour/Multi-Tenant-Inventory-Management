#!/usr/bin/env bash
set -o errexit

echo "--- Installing dependencies ---"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "--- Caching configuration ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Build complete ---"
