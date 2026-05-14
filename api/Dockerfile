FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    zip \
    opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN cp docker/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN composer install --no-interaction --prefer-dist

CMD ["php-fpm"]
