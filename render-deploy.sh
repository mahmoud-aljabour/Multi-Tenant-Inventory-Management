#!/usr/bin/env bash
set -o errexit

echo "--- Running migrations ---"
php artisan migrate --force

if [ "${SEED_DEMO_DATA}" = "true" ]; then
    echo "--- Seeding demo data ---"
    php artisan db:seed --force
fi

echo "--- Deploy complete ---"
