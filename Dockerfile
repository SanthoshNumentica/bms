# Use official PHP 8.1 FPM image
FROM php:8.1-fpm

# Install system dependencies and PHP extensions required by Laravel & Filament
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    curl \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip mbstring gd bcmath

# Install Composer (dependency manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Increase PHP memory limit for Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Install PHP dependencies via Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Cache config and routes for better performance
RUN php artisan config:cache
RUN php artisan route:cache

# Set permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 (php-fpm default)
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
