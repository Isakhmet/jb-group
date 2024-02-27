FROM php:8.0-fpm

COPY php.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        libpq-dev \
        && docker-php-ext-install zip \
        && docker-php-ext-install exif \
        && docker-php-ext-install pdo pdo_pgsql pgsql


RUN apt-get update && apt-get install -y \
    libzip-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip xml gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
