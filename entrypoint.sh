#!/bin/sh
set -e

php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g 'daemon off;'
