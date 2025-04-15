FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www/html