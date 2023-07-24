FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        libpq-dev \
        && docker-php-ext-install zip \
        && docker-php-ext-install exif \
        && docker-php-ext-install pdo pdo_pgsql pgsql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
