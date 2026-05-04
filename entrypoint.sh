#!/bin/sh
set -e

php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start PHP-FPM and Nginx
service php8.2-fpm start
nginx -g 'daemon off;'
