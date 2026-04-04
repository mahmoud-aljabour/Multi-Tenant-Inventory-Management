#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Running Composer ---"
composer install --no-dev --optimize-autoloader

echo "--- Optimizing Laravel ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Running Migrations ---"
# ملاحظة: سيتم تشغيل المايجريشن فقط إذا كانت قاعدة البيانات مهيأة
# php artisan migrate --force