# Use official PHP 8.1 image with FPM
FROM php:8.1-fpm

# Install system dependencies and PHP extensions needed for Laravel & Filament
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

# Copy existing application files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Cache Laravel config & routes for performance
RUN php artisan config:cache
RUN php artisan route:cache

# Set permissions (if needed)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for php-fpm
EXPOSE 9000

# Start php-fpm server
CMD ["php-fpm"]
