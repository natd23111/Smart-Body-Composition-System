# Use official PHP-Apache image
FROM php:8.2-apache

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
COPY .env.example .env

# Fix permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Install Node/Vite dependencies and build assets
RUN npm install && npm run build

# Note: Run `php artisan config:clear` and `php artisan cache:clear` at runtime (not during build) if needed.

# Enable Apache mod_rewrite (required for Laravel)
RUN a2enmod rewrite

# Copy custom Apache config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# (Nginx not used with Apache image, skip copying nginx.conf)

# (Not needed for mod_php)

# Expose Apache default port
EXPOSE 80

# Copy entrypoint script and set permissions
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Start entrypoint script (which should start Apache in foreground)
ENTRYPOINT ["/entrypoint.sh"]
# entrypoint.sh should start php-fpm and nginx
