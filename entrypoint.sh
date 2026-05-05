#!/bin/sh
set -e


# Force debug mode for troubleshooting
export APP_DEBUG=true
export APP_ENV=local

php artisan config:clear
php artisan cache:clear
php artisan migrate --force

# Start Apache (httpd)
apachectl -D FOREGROUND
