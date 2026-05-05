#!/bin/sh
set -e

php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start Apache (httpd)
apachectl -D FOREGROUND
