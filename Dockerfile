FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev curl zip libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip mbstring gd bcmath intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_MEMORY_LIMIT=-1

RUN composer clear-cache
RUN composer install --no-interaction --prefer-dist --optimize-autoloader -vvv

RUN php artisan config:cache
RUN php artisan route:cache

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
