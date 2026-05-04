# Use official PHP image with extensions
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    curl \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .


# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy production environment file for build
COPY .env.production .env

# Install Node/Vite dependencies and build assets
RUN npm install && npm run build

# Note: Run `php artisan config:clear` and `php artisan cache:clear` at runtime (not during build) if needed.

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Copy nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Configure PHP-FPM to use a Unix socket
RUN sed -i 's|listen = 9000|listen = /var/run/php/php-fpm.sock|' /usr/local/etc/php-fpm.d/www.conf

# Expose port
EXPOSE 10000

# Copy entrypoint script and set permissions
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Start entrypoint script, then PHP-FPM and Nginx
ENTRYPOINT ["/entrypoint.sh"]
# entrypoint.sh should start php-fpm and nginx
