#!/bin/sh
set -e



# Force debug mode for troubleshooting
export APP_DEBUG=true
export APP_ENV=local

echo "--- ENVIRONMENT VARIABLES ---"
env

echo "--- LARAVEL .env FILE ---"
cat /var/www/.env || echo ".env not found"

echo "--- LARAVEL CONFIG ---"
php artisan env
php artisan config:show || echo "config:show not available"

echo "--- CLEARING CONFIG AND CACHE ---"
php artisan config:clear
php artisan cache:clear

echo "--- RUNNING MIGRATIONS ---"
php artisan migrate --force

echo "--- LARAVEL LOGS ---"
tail -n 40 /var/www/storage/logs/laravel.log || echo "No laravel.log found"

# Start Apache (httpd)
apachectl -D FOREGROUND
