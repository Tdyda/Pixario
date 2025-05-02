FROM php:8.4-fpm

# Zainstaluj potrzebne rozszerzenia
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libonig-dev libxml2-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip

# Symfony CLI (opcjonalnie, ale pomocne dla developmentu)
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN echo "upload_max_filesize=5M\npost_max_size=12M" > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html